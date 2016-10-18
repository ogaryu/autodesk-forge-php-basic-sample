<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/03
 * Time: 午後4:54
 */

namespace Autodesk\Forge\PHP\Sample\Core;


class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    /**
     * Session constructor.
     * 
     * Start session automatically
     */
    public function __construct()
    {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    /**
     * Set value to the session
     * 
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Get value from the session
     * 
     * @param $name
     * @param null $default
     * @return null
     */
    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * Remove value in the session
     * 
     * @param $name
     */
    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Clear session
     */
    public function clear()
    {
        $_SESSION = array();
    }

    /**
     * Regenerate session id
     *
     * @param boolean $destroy true will delete old session
     */
    public function regenerate($destroy = true)
    {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
    }

    /**
     * Set authentication status
     * 
     * @param $bool
     */
    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    /**
     * Check if already authenticated
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}