<?php


namespace Engine\Service\Setting;



use Engine\Core\Setting\Setting;
use Engine\Service\AbstractProvider;

/**
 * Class Provider
 * @package Engine\Service\Setting
 */
class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    function init()
    {
        $this->di->set('setting', new Setting($this->di));
    }
}