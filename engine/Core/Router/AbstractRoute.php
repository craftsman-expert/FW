<?php


namespace Engine\Core\Router;


use Engine\DI\DI;

abstract class AbstractRoute
{
    /**
     * @var DI
     */
    protected $di;


    /**
     * AbstractProvider constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }
}