<?php


namespace Engine\Service\Http;


use Engine\Service\AbstractProvider;
use GuzzleHttp\Client;

/**
 * Class Provider
 * @package Engine\Service\http
 */
class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    function init()
    {
        $this->di->set('http', new Client());
    }
}