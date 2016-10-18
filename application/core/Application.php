<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/03
 * Time: 午後5:01
 */

namespace Autodesk\Forge\PHP\Sample\Core;

use Autodesk\Forge\PHP\Sample\Core\Request;
use Autodesk\Forge\PHP\Sample\Core\Response;
use Autodesk\Forge\PHP\Sample\Core\Session;
use Autodesk\Forge\PHP\Sample\Core\Router;
use Autodesk\Forge\PHP\Sample\Core\HttpNotFoundException;
use Autodesk\Forge\PHP\Sample\Core\UnauthorizedActionException;

require_once 'Request.php';
require_once 'Response.php';
require_once 'Session.php';
require_once 'Router.php';
require_once 'HttpNotFoundException.php';
require_once 'UnauthorizedActionException.php';

abstract class Application
{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;

    /**
     * Application constructor.
     * 
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /**
     * setup debug mode
     *
     * @param boolean $debug
     */
    protected function setDebugMode($debug)
    {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }

    /**
     * Initialize application
     */
    protected function initialize()
    {
        $this->request    = new Request();
        $this->response   = new Response();
        $this->session    = new Session();
        $this->router     = new Router($this->registerRoutes());
    }

    /**
     * Configure application
     */
    protected function configure()
    {
    }

    /**
     * Get project root directory
     *
     * @return string ルートディレクトリへのファイルシステム上の絶対パス
     */
    abstract public function getRootDir();

    /**
     * Get routes
     *
     * @return array
     */
    abstract protected function registerRoutes();

    /**
     * Check if debug mode
     *
     * @return boolean
     */
    public function isDebugMode()
    {
        return $this->debug;
    }

    /**
     * Get request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get session object
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get controllers directory
     *
     * @return string
     */
    public function getControllerDir()
    {
        return $this->getRootDir() . '/controllers';
    }

    /**
     * Get views directory
     *
     * @return string
     */
    public function getViewDir()
    {
        return $this->getRootDir() . '/views';
    }

    /**
     * Get models directory
     *
     * @return stirng
     */
    public function getModelDir()
    {
        return $this->getRootDir() . '/models';
    }

    /**
     * Get document route
     *
     * @return string
     */
    public function getWebDir()
    {
        return $this->getRootDir() . '/public';
    }

    /**
     * Run application
     *
     * @throws HttpNotFoundException ルートが見つからない場合
     */
    public function run()
    {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());

            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);
        } catch (UnauthorizedActionException $e) {
            list($controller, $action) = $this->login_action;
            $this->runAction($controller, $action);
        }

        $this->response->send();
    }

    /**
     * Run action
     *
     * @param string $controller_name
     * @param string $action
     * @param array $params
     *
     * @throws HttpNotFoundException コントローラが特定できない場合
     */
    public function runAction($controller_name, $action, $params = array())
    {
        $controller_class = ucfirst($controller_name) . 'Controller';

        $controller = $this->findController($controller_class);
        if ($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        $this->response->setContent($content);
    }

    /**
     * Get controller object from controller name
     *
     * @param string $controller_class
     * @return Controller
     */
    protected function findController($controller_class)
    {
        if (!class_exists($controller_class)) {
            
            $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
            
            if (!is_readable($controller_file)) {
                return false;
            } else {
                require_once($controller_file);

                if (!class_exists($controller_class)) {

                    return false;
                }
            }
        }
        
        return new $controller_class($this);
    }

    /**
     * Return 404 error view
     *
     * @param Exception $e
     */
    protected function render404Page($e)
    {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(<<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>404</title>
            </head>
            <body>
                {$message}
            </body>
            </html>
EOF
        );
    }
}