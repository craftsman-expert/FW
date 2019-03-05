<?php

namespace Engine\Service\Router;


use Engine\Core\Config\Config;
use Engine\Service\AbstractProvider;
use Engine\Core\Router\Router;

/**
 * Class Provider
 */
class Provider extends AbstractProvider
{

    /**
     * @var string
     */
    public $serviceName = 'router';

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function init()
    {
        $router = new Router(Config::item('HTTP_CATALOG'));
        $this->di->set($this->serviceName, $router);
    }
}