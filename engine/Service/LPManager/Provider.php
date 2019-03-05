<?php


namespace Engine\Service\LPManager;

use Engine\Core\LPManager\LPManager;
use Engine\Service\AbstractProvider;

/**
 * Class Provider
 * @package Engine\Service\LPManager
 */
class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    function init()
    {
        $this->di->set('lpm', new LPManager($this->di));
    }
}