<?php


namespace Engine\Core\Router;
use Engine\Helper\Common;
use Engine\Helper\Server;


/**
 * Class Route
 * @package Engine\Core\Router
 */
class Route extends AbstractRoute
{

    /**
     * @var \Engine\Core\Router\Router
     */
    private $route;



    public function __construct(\Engine\DI\DI $di)
    {
        parent::__construct($di);
        $this->route = $this->di->get('router');
    }



    /**
     * Method GET
     * @param $pattern
     * @param $controller
     * @param string $type
     */
    public function get($pattern, $controller, $type = 'PAGE')
    {
        $controller = str_replace('/', '\\', $controller);
        $this->route->get($pattern, $controller, $type);
    }



    /**
     * Method POST
     * @param $pattern
     * @param $controller
     * @param string $type
     */
    public function post($pattern, $controller, $type = 'DATA')
    {
        $controller = str_replace('/', '\\', $controller);
        $this->route->post($pattern, $controller, $type);
    }



    /**
     * Any method
     * @param $pattern
     * @param $controller
     * @param string $type
     */
    public function mixed($pattern, $controller, $type = 'DATA')
    {
        $this->route->add(
            str_replace('/', '.', $pattern),
            $pattern,
            str_replace('/', '\\', $controller),
            $_SERVER['REQUEST_METHOD'],
            $type
        );
    }

}