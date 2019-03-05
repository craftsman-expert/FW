<?php

namespace Engine\Core\Router;

/**
 * Class DispatchedRoute
 * @package Engine\Core\Router
 */
class DispatchedRoute
{
    private $controller;
    private $parameters;
    private $dir;



    /**
     * DispatchedRoute constructor.
     * @param $controller
     * @param array $parameters
     * @param bool $dir
     */
    public function __construct($controller, $parameters = [], $dir = false)
    {
        $this->controller = $controller;
        $this->parameters = $parameters;
    }



    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }



    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }



    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}