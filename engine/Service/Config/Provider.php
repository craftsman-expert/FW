<?php

namespace Engine\Service\Config;

use Engine\Core\Config\Config;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    /**
     * @var string
     */
    public $serviceName = 'config';



    /**
     * @return mixed
     * @throws \Exception
     */
    public function init()
    {
        $config['main'] = Config::file('main');
        $config['database'] = Config::file('database');
        $this->di->set($this->serviceName, $config);
    }
}