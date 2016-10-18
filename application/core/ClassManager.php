<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2016/10/01
 * Time: 午後3:07
 */

namespace Autodesk\Forge\PHP\Sample\Core;


class ClassManager
{
    protected $dirs;

    /**
     * Register autoload 
     */
    public function registerClass()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Register target directories
     *
     * @param string $dir
     */
    public function registerClassDir($dir)
    {
        $this->dirs[] = $dir;
    }

    /**
     * Load class
     *
     * @param string $class
     */
    public function loadClass($class)
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}