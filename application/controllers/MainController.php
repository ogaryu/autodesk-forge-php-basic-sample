<?php

/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/17
 * Time: 午後11:25
 */

use Autodesk\Forge\PHP\Sample\Core\Controller;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Exception\RequestException;
use League\OAuth2\Client\Provider;

require APP . 'core/Controller.php';
require LIB . 'vendor/autoload.php';

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render();
    }

    public function loginAction()
    {
        $config = require(APP . '/config/config.php');
        
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $config['client_id'],
            'clientSecret'            => $config['client_secret'],
            'redirectUri'             => $config['redirect_uri'],
            'urlAuthorize'            => $config['oauth_endpoint'],
            'urlAccessToken'          => $config['token_endpoint'],
            'urlResourceOwnerDetails' => $config['resource'],
            'scope' => $config['scope']
        ]);

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $this->session->set('oauth2state', $provider->getState());

            // Redirect the user to the authorization URL.
            // header('Location: ' . $authorizationUrl);
            return $this->json_response(200, json_encode(['url'=> $authorizationUrl . '&response_type=code&scope=' . $config['scope']]));

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            $this->session->remove('oauth2state');
            exit('Invalid state');

        }

        return $this->render();
    }

    public function logoutAction()
    {
        if($this->session->get('token') != ""){
            $this->session->remove('token');
            $this->session->remove('refresh_token');
            $this->token = "";
            $this->refresh_token = "";
        }

        return $this->json_response(200, json_encode(['message'=> 'logout successful']));
    }

    public function getAccessTokenAction(){

        try {

            $config = require(APP . '/config/config.php');

            $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => $config['client_id'],
                'clientSecret'            => $config['client_secret'],
                'redirectUri'             => $config['redirect_uri'],
                'urlAuthorize'            => $config['oauth_endpoint'],
                'urlAccessToken'          => $config['token_endpoint'],
                'urlResourceOwnerDetails' => $config['resource'],
                'scope' => $config['scope']
            ]);

            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' =>  $this->request->getGetParameters('code')
            ]);

            $this->session->set('token', $accessToken->getToken());
            $this->session->set('refresh_token', $accessToken->getRefreshToken());

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

            // Failed to get the access token or user details.
            exit($e->getMessage());
        }
        
        return '<script>window.close();</script>';
    }
    
    public function getUserProfileAction(){

        $content = "";
        $request = 'https://developer.api.autodesk.com/userprofile/v1/users/@me';

        if($this->session->get('token') != null){
            try {
                
                $client = new \GuzzleHttp\Client();
                
                $response = $client->request('GET', $request, [
                    'headers' => [
                        'Authorization'      => 'Bearer ' . $this->session->get('token')
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);
                
            } catch (RequestException $e) {
                return $this->json_response(200, json_encode(['message'=> $e->getMessage()]));
            }

            $content = $response->getBody()->getContents();
        }
        else {
            return $this->json_response(200, json_encode(['message'=> 'Field token is expired. Please try to login.']));
        }
        
        $result = json_encode([
            'request'=>$request,
            'content'=>$content
        ]);

        return $this->json_response(200, $result);
    }

    public function getHubsAction(){

        $content = "";
        $request = 'https://developer.api.autodesk.com/project/v1/hubs';

        if($this->session->get('token') != null) {

            try {
                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', $request, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->session->get('token')
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);
                
                $content = $response->getBody()->getContents();
                
            } catch (RequestException $e) {
                return $this->json_response(200, json_encode(['message'=> $e->getMessage()]));
            }
        }
        else {
            return $this->json_response(200, json_encode(['message'=> 'Field token is expired. Please try to login.']));
        }

        $result = json_encode([
            'request'=>$request,
            'content'=>$content
        ]);

        return $this->json_response(200, $result);
    }
    
    public function getProjectsAction($params){

        $content = "";
        $request = 'https://developer.api.autodesk.com/project/v1/hubs/' . $params['hub_id'] . '/projects';

        if($this->session->get('token') != null) {

            try {
                
                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', $request, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->session->get('token')
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);

                $content = $response->getBody()->getContents();
                
            } catch (RequestException $e) {
                return $this->json_response(200, json_encode(['message'=> $e->getMessage()]));
            }
        }
        else {
            return $this->json_response(200, json_encode(['message'=> 'Field token is expired. Please try to login.']));
        }

        $result = json_encode([
            'request'=>$request,
            'content'=>$content
        ]);

        return $this->json_response(200, $result);
    }

    public function getItemsAction($params){

        $content = "";
        $request = 'https://developer.api.autodesk.com/data/v1/projects/'. $params['project_id'] .'/folders/'. $params['folder_id'] .'/contents';

        if($this->session->get('token') != null) {

            try {

                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', $request, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->session->get('token')
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);

                $content = $response->getBody()->getContents();

            } catch (RequestException $e) {
                return $this->json_response(200, json_encode(['message'=> $e->getMessage()]));
            }
        }
        else {
            return $this->json_response(200, json_encode(['message'=> 'Field token is expired. Please try to login.']));
        }

        $result = json_encode([
            'request'=>$request,
            'content'=>$content
        ]);

        return $this->json_response(200, $result);
    }
    
    public function getTokenAction() {

        return $this->session->get('token');
    }
    
}
