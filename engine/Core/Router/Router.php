<?php


namespace Engine\Core\Router;


/**
 * Class Router
 * @package Engine\Core\Router
 */
class Router
{
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var
     */
    private $dispatcher;
    /**
     * @var
     */
    private $host;



    /**
     * Router constructor.
     *
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;

    }



    /**
     * @param        $key
     * @param        $pattern
     * @param        $controller
     * @param string $method
     * @param string $type
     */
    public function add($key, $pattern, $controller, $method = 'GET', $type = 'PAGE')
    {
        $this->routes[$key] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'method' => $method,
            'type' => $type,
        ];
    }



    /**
     * @param        $pattern
     * @param        $controller
     * @param string $type
     */
    public function get($pattern, $controller, $type = 'PAGE')
    {
        $key = str_replace('/', '.', $pattern);
        $this->add($key, $pattern, $controller, 'GET', $type);
    }



    /**
     * @param        $pattern
     * @param        $controller
     * @param string $type
     */
    public function post($pattern, $controller, $type = 'PAGE')
    {
        $key = str_replace('/', '.', $pattern);
        $this->add($key, $pattern, $controller, 'POST', $type);
    }



    /**
     * @param $method
     * @param $uri
     *
     * @return DispatchedRoute
     */
    public function dispatch($method, $uri)
    {
        return $this->getDispatcher()->dispatch($method, $uri);
    }



    /**
     * @return UrlDispatcher
     */
    public function getDispatcher()
    {
        if ($this->dispatcher == null) {
            $this->dispatcher = new UrlDispatcher();
            foreach ($this->routes as $route) {
                $this->dispatcher->register($route['method'], $route['pattern'], $route['controller']);
            }
        }
        return $this->dispatcher;
    }



    /**
     * @return array
     */
    public function getRoutes():array
    {
        return $this->routes;
    }


}


class UrlDispatcher
{
    /**
     * @var array
     */
    private $methods = ['GET', 'POST'];
    /**
     * @var array
     */
    private $routes = ['GET' => [], 'POST' => []];
    /**
     * @var array
     */
    private $patterns = [
        'int' => '[0-9]+',
        'str' => '[a-zA-Z\.\-_%]+',
        'any' => '[a-zA-Z0-9\.\-_%]+'
    ];



    /**
     * @param $key
     * @param $pattern
     */
    public function addPattern($key, $pattern)
    {
        $this->patterns[$key] = $pattern;
    }



    /**
     * @param $method
     *
     * @return array|mixed
     */
    private function routes($method)
    {
        return isset($this->routes[$method]) ? $this->routes[$method] : [];
    }



    /**
     * @param $method
     * @param $pattern
     * @param $controller
     */
    public function register($method, $pattern, $controller)
    {
        $convert = $this->convertPattern($pattern);
        $this->routes[strtoupper($method)][$convert] = $controller;
    }



    /**
     * @param $pattern
     *
     * @return mixed
     */
    private function convertPattern($pattern)
    {
        if (strpos($pattern, '(') === false) {
            return $pattern;
        }
        return preg_replace_callback('#\((\w+):(\w+)\)#', [$this, 'replacePattern'], $pattern);
    }



    /**
     * @param $matches
     *
     * @return string
     */
    private function replacePattern($matches)
    {
        return '(?<' . $matches[1] . '>' . strtr($matches[2], $this->patterns) . ')';
    }



    /**
     * @param $parameters
     *
     * @return mixed
     */
    private function processParam($parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_int($key)) {
                unset($parameters[$key]);
            }
        }
        return $parameters;
    }



    /**
     * @param $method
     * @param $uri
     *
     * @return DispatchedRoute|void
     */
    public function dispatch($method, $uri)
    {
        $routes = $this->routes(strtoupper($method));
        if (array_key_exists($uri, $routes)) {
            /** @noinspection PhpInconsistentReturnPointsInspection */
            return new DispatchedRoute($routes[$uri]);
        }
        /** @noinspection PhpInconsistentReturnPointsInspection */
        return $this->doDispatch($method, $uri);
    }



    /**
     * @param $method
     * @param $uri
     *
     * @return DispatchedRoute
     */
    private function doDispatch($method, $uri)
    {
        foreach ($this->routes($method) as $route => $controller) {
            $pattern = '#^' . $route . '$#s';
            if (preg_match($pattern, $uri, $parameters)) {
                return new DispatchedRoute($controller, $this->processParam($parameters));
            }
        }
    }


}