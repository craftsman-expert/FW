<?php


namespace Engine\Service\Memcached;


use Engine\Service\AbstractProvider;
use Memcache;


/**
 * Class Provider
 * @package Engine\Service\Memcache
 */
class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    function init()
    {
        $memcache = new Memcache();
        $memcache->pconnect('localhost') or die('Could not connect');

        $this->di->set('memcache', $memcache);
    }
}