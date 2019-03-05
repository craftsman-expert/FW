<?php


namespace Engine\Service\NexmoClient;


use Engine\Service\AbstractProvider;
use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

/**
 * Class Provider
 * @package Engine\Service\NexmoClient
 */
class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    function init()
    {
        $nexmo_message_client = new Client(new Basic('5506b8f1', 'ZCNQ4JrtHeAs5qmC'));
        $this->di->set('nexmo_message_client', $nexmo_message_client);
    }
}