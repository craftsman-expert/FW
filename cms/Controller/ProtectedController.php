<?php

namespace Cms\Controller;


use Cms\Model\Customer\Customer;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Cookie;
use Engine\Helper\Header;
use Engine\Helper\Message;
use Engine\Helper\Network;

class ProtectedController extends CmsController
{


    /**
     * @var Customer
     */
    protected $customer;


    /**
     * ProtectedController constructor.
     *
     * @param DI $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->customer = $this->load->model('Customer');

        if (!$this->verification()){

            switch (Common::getCurrentPatternType()){
                case "PAGE": {
                    Network::location('/sigIn');
                }

                case "DATA": {
                    Message::warning($this->lpm->translate('message', 'user_access_denied'), USER_AUTHORIZATION_REQUIRED);
                }
            }
        }

    }

}