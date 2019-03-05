<?php

namespace Engine;


use Engine\DI\DI;
use Monolog\Logger;

/**
 * Class Service
 * @package Engine
 */
abstract class Service
{
    /**
     * @var DI
     */
    protected $di;
    /**
     * @var SafeMySQL
     */
    protected $db;
    /**
     * @var Load
     */
    protected $load;
    /**
     * @var AbstractModel
     */
    protected $model;
    /**
     * @var Logger
     */
    protected $logger;



    /**
     * Service constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->db = $this->di->get('db');
        $this->load = $this->di->get('load');
        $this->model = $this->di->get('model');
        $this->logger = $this->di->get('logger');
    }



    /**
     * @return DI
     */
    public function getDI()
    {
        return $this->di;
    }



    /**
     * @return bool|SafeMySQL
     */
    public function getDb()
    {
        return $this->db;
    }



    /**
     * @return Load
     */
    public function getLoad()
    {
        return $this->load;
    }



    /**
     * @param $name
     * @return object
     * @throws \Exception
     */
    public function getModel($name)
    {
        $this->load->model(ucfirst($name));
        $model = $this->getDI()->get('model');
        return $model->{lcfirst($name)};
    }
}