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
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            // header('Location: ' . $authorizationUrl);
            return $this->json_response(200, json_encode(['url'=> $authorizationUrl . '&response_type=code']));

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        }

        return $this->render();
    }

    public function logoutAction()
    {
        if(Session::get('token') != ""){
            Session::forget('token');
            Session::forget('refresh_token');
            $this->token = "";
            $this->refresh_token = "";
        }
        
        return $this->redirect('/');
    }
    
}
