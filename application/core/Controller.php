<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/03
 * Time: 午後6:44
 */

namespace Autodesk\Forge\PHP\Sample\Core;

use Autodesk\Forge\PHP\Sample\Core\View;
use Autodesk\Forge\PHP\Sample\Core\HttpNotFoundException;
use Autodesk\Forge\PHP\Sample\Core\UnauthorizedActionException;

require_once 'View.php';
require_once 'HttpNotFoundException.php';
require_once 'UnauthorizedActionException.php';

abstract class Controller
{
    protected $controller_name;
    protected $action_name;
    protected $application;
    protected $request;
    protected $response;
    protected $session;
    protected $auth_actions = array();

    /**
     * Controller constructor.
     * 
     * @param $application
     */
    public function __construct($application)
    {
        $this->controller_name = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request     = $application->getRequest();
        $this->response    = $application->getResponse();
        $this->session     = $application->getSession();
    }

    /**
     * Run action
     *
     * @param $action
     * @param array $params
     * @return mixed contents
     * @throws HttpNotFoundException
     * @throws UnauthorizedActionException
     */
    public function run($action, $params = array())
    {
        $this->action_name = $action;
        
        $action_method = $action . 'Action';
        
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
            throw new UnauthorizedActionException();
        }

        $content = $this->$action_method($params);

        return $content;
    }

    /**
     * Render view
     * 
     * @param array $variables: pass to its template
     * @param null $template: view file name(if null, use action name)
     * @param string $layout: layout file name
     * @return mixed: rendered view file content
     */
    protected function render($variables = array(), $template = null, $layout = 'layout')
    {
        $defaults = array(
            'request'  => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session'  => $this->session,
        );

        $view = new View($this->application->getViewDir(), $defaults);

        if (is_null($template)) {
            $template = $this->action_name;
        }

        $path = $this->controller_name . '/' .$template;

        return $view->render($path, $variables, $layout);
    }
    
    /**
     * Forward 404 error view
     * 
     * @throws HttpNotFoundException
     */
    protected function forward404()
    {
        throw new HttpNotFoundException('Forwarded 404 page from '
            . $this->controller_name . '/' . $this->action_name);
    }

    /**
     * Redirect to the url
     * 
     * @param $url
     */
    protected function redirect($url)
    {
        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->request->isSsl() ? 'https://' : 'http://';
            $host = $this->request->getHost();
            $base_url = $this->request->getBaseUrl();

            $url = $protocol . $host . $base_url . $url;
        }

        $this->response->setStatusCode(302, 'Found');
        $this->response->setHttpHeader('Location', $url);
    }

    /**
     * @param null $message
     * @param int $code
     * @return mixed
     */
    protected function json_response($data = null, $code = 200)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        header('Status: '.$status[$code]);
        // return the encoded json
        return $data;
    }
    
    /**
     * Create CSRF token
     * 
     * @param $form_name
     * @return mixed $token
     */
    protected function generateCsrfToken($form_name)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);

        return $token;
    }
    
    /**
     * Check if CSRF token is valid
     * 
     * @param $form_name
     * @param $token
     * @return bool
     */
    protected function checkCsrfToken($form_name, $token)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());

        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $this->session->set($key, $tokens);

            return true;
        }

        return false;
    }

    /**
     * Check if the target action needs to be authenticated
     * 
     * @param $action
     * @return bool
     */
    protected function needsAuthentication($action)
    {
        if ($this->auth_actions === true
            || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))
        ) {
            return true;
        }

        return false;
    }
}