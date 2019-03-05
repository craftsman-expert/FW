<?php

namespace Engine;

use Engine\Core\Config\Config;
use Engine\Core\Database\SafeMySQL;
use Engine\DI\DI;
use Memcache;

/**
 * Class Model
 * @package Engine
 */
abstract class AbstractModel
{
    /**
     * @var DI
     */
    protected $di;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Load
     */
    protected $load;
    /**
     * @var SafeMySQL
     */
    protected $db;
    /**
     * @var Memcache
     */
    protected $memcache;



    /**
     * Model constructor.
     * @param $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->db = $this->di->get('db');
        $this->memcache = $this->di->get('memcache');
        $this->config = $this->di->get('config');
        $this->load = $this->di->get('load');
    }



    /**
     * @return SafeMySQL
     */
    protected function db():SafeMySQL
    {
        return $this->db;
    }


}