<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/01
 * Time: 午後3:31
 */

namespace Autodesk\Forge\PHP\Sample\Core;


class Router
{
    protected $routes;

    /**
     * Router constructor.
     * @param $definitions
     */
    public function __construct($definitions)
    {
        $this->routes = $this->settingRoutes($definitions);
    }

    /**
     * Setting routes
     * 
     * @param $definitions
     * @return array
     */
    public function settingRoutes($definitions)
    {
        $routes = array();

        foreach ($definitions as $url => $params) {
            $tokens = explode('/', ltrim($url, '/'));
            foreach ($tokens as $i => $token) {
                if (0 === strpos($token, ':')) {
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }

            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;

        }
        return $routes;
    }

    /**
     * Get routing parameters from path_info
     * 
     * @param $path_info
     * @return bool|mixed
     */
    public function resolve($path_info)
    {
        if ('/' !== substr($path_info, 0, 1)) {
            $path_info = '/' . $path_info;
        }

        foreach ($this->routes as $pattern => $params) {
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                $params = array_merge($params, $matches);

                return $params;
            }
        }

        return false;
    }
}