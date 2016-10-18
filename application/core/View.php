<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/03
 * Time: 午後6:54
 */

namespace Autodesk\Forge\PHP\Sample\Core;


class View
{
    protected $base_dir;
    protected $defaults;
    protected $layout_variables = array();
    
    /**
     * View constructor.
     * 
     * @param $base_dir
     * @param array $defaults
     */
    public function __construct($base_dir, $defaults = array())
    {
        $this->base_dir = $base_dir;
        $this->defaults = $defaults;
    }
    
    /**
     * Set lyaout variables
     * 
     * @param $name
     * @param $value
     */
    public function setLayoutVar($name, $value)
    {
        $this->layout_variables[$name] = $value;
    }
    
    /**
     * Render view file
     * 
     * @param $_path
     * @param array $_variables
     * @param bool $_layout
     * @return mixed
     */
    public function render($_path, $_variables = array(), $_layout = false)
    {

        $_file = $this->base_dir . '/' . $_path . '.php';
        

        extract(array_merge($this->defaults, $_variables));

        ob_start();
        ob_implicit_flush(0);

        require $_file;

        $content = ob_get_clean();

        if ($_layout) {
            $content = $this->render($_layout,
                array_merge($this->layout_variables, array(
                        '_content' => $content,
                    )
                ));
        }

        return $content;
    }
    
    /**
     * Escape string to HTML 
     * 
     * @param $string
     * @return mixed
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}