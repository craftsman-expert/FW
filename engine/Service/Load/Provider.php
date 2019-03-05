<?php
namespace Engine\Service\Load;

use Engine\Service\AbstractProvider;
use Engine\Load;

class Provider extends AbstractProvider
{

    /**
     * @return mixed
     */
    public function init()
    {
        $load = new Load($this->di);
        $this->di->set('load', $load);
        return $this;
    }
}