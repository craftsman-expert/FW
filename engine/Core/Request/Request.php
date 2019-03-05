<?php

namespace Engine\Core\Request;

use Exception;

class Request
{
    /**
     * @var array
     */
    public $get = [];

    /**
     * @var array
     */
    public $post = [];

    /**
     * @var array
     */
    public $request = [];

    /**
     * @var array
     */
    public $cookie = [];

    /**
     * @var array
     */
    public $files = [];

    /**
     * @var array
     */
    public $server = [];



    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->setPost($_POST);
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }


    /**
     * @param bool|mixed $key
     * @param null $default
     * @return array|mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }



    /**
     * @param bool|mixed $key
     * @param null $default
     * @return array|mixed
     */
    public function post($key = false, $default = null)
    {
        return !empty($key ? $this->post[$key] : $this->post) ? $this->post[$key] : $default;
    }



    /**
     * @param $key
     * @param bool $default
     * @return bool|mixed
     */
    public function mixed($key, $default = false)
    {
        return !empty($this->request[$key]) ? $this->request[$key] : $default;
    }



    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if (empty($this->request[$key])){
            array_push($this->request, [$key => $value]);
        } else {
            $this->request[$key] = $value;
        }
    }


    /**
     * @return array
     * @throws Exception
     */
    public function getPost():array
    {
        if (empty($this->post)) throw new Exception("Параметр POST несодержит данных", 1000);
        return $this->post;
    }



    /**
     * @param array $post
     */
    public function setPost(array $post):void
    {
        $this->post = $post;
    }



    /**
     * @return string
     */
    public function requestUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }



    /**
     * @param string $stream
     * @return bool|string
     */
    public  function getContent($stream = 'php://input')
    {
        return file_get_contents($stream);
    }



    /**
     * @return mixed|null
     */
    public function jsonObj()
    {
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        return is_object($obj) ? $obj : null;
    }
}
