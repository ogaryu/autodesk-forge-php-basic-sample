<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/10/03
 * Time: 午後4:50
 */

namespace Autodesk\Forge\PHP\Sample\Core;


class Response
{
    protected $content;
    protected $status_code = 200;
    protected $status_text = 'OK';
    protected $http_headers = array();

    /**
     * Send response
     */
    public function send()
    {
        header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);

        foreach ($this->http_headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    /**
     * Set contents
     * 
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Set status code
     * 
     * @param $status_code
     * @param string $status_text
     */
    public function setStatusCode($status_code, $status_text = '')
    {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
    }

    /**
     * Set response header
     * 
     * @param $name
     * @param $value
     */
    public function setHttpHeader($name, $value)
    {
        $this->http_headers[$name] = $value;
    }
}