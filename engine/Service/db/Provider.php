<?php
namespace Engine\Service\db;

use Engine\Core\Config\Config;
use Engine\Core\Database\SafeMySQL;
use Engine\Service\AbstractProvider;


/**
 * Class Provider
 * @package Engine\Service\Database
 */
class Provider extends AbstractProvider
{


    /**
     * @return mixed
     * @throws \Exception
     */
    public function init()
    {
        $opt = Config::group('database');
        $this->di->set('db', new SafeMySQL($opt));

    }
}