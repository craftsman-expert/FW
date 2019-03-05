<?php
/**
 * Created by PhpStorm.
 * User: igorp
 * Date: 26.07.2018
 * Time: 16:34
 */

namespace Engine\Service\Logger;


use Engine\Helper\PHPOptions;
use Engine\Helper\Server;
use Engine\Service\AbstractProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


/**
 * Class Provider
 * @package Engine\Service\Logger
 */
class Provider extends AbstractProvider
{

    /**
     * @var string
     */
    public $serviceName = 'logger';



    /**
     * @return mixed
     * @throws \Exception
     */
    public function init()
    {
        $logger = new Logger('monolog');

        $logger->pushHandler(new StreamHandler(PHPOptions::getTempDir() . '/logs/debug.log', Logger::DEBUG, false));
        $logger->pushHandler(new StreamHandler(PHPOptions::getTempDir() . '/logs/info.log', Logger::INFO, false));
        $logger->pushHandler(new StreamHandler(PHPOptions::getTempDir() . '/logs/warning.log', Logger::WARNING, false));
        $logger->pushHandler(new StreamHandler(PHPOptions::getTempDir() . '/logs/notice.log', Logger::NOTICE, false));
        $logger->pushHandler(new StreamHandler(PHPOptions::getTempDir() . '/logs/critical.log', Logger::CRITICAL, false));

        $this->di->set('logger', $logger);
    }

}