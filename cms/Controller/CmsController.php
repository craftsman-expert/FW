<?php

namespace Cms\Controller;

use Cms\Model\Customer\Customer;
use Engine\AbstractController;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Cookie;
use Engine\Helper\Header;
use Engine\Helper\Server;
use General\Model\RequestHistory\RequestHistory;


/**
 * Class CmsController
 * @property mixed model
 * @package Cms\Controller
 */
class CmsController extends AbstractController
{

    /**
     * @var string
     */
    private $session_id;
    /**
     * @var string
     */
    private $access_token;
    /**
     * Customer id
     * @var
     */
    private $customer_id = 0;


    /**
     * @var RequestHistory
     */
    protected $request_history;

    /** @var Customer */
    protected $customer;



    /**
     * CmsController constructor.
     *
     * @param $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->customer = $this->load->model('Customer');

        // todo: Developer
        // if (!Cookie::get('dev_id', false) == '95f340bd04477'){
        //     $this->engineeringWorks(); exit;
        // }

        $this->initializingDataToVerify();

        $this->lpm->load('main', $this->lang);
        $this->lpm->load('catalog', $this->lang);
        $this->lpm->load('shop', $this->lang);
        $this->lpm->load('profile', $this->lang);
        $this->lpm->load('message', $this->lang);
        $this->lpm->load('page', $this->lang);

        // loading models
        // $this->request_history = $this->load->model('requestHistory', 'requestHistory', 'General');
        //
        // $this->request_history->add(
        //     $this->getAppSessionId(),
        //     Server::getRequestURI(),
        //     Common::getCurrentPatternType(),
        //     Server::getRequestMethod());

        $this->twig->addGlobal('AUTHORIZED', $this->verification()) ;
        $this->twig->addGlobal('HEADER_TITLE',  $this->lpm->translate('page', strtolower(Common::getPath('_')) . '_title'));
        $this->twig->addGlobal('HEADER_TITLE_KEY',  strtolower(Common::getPath('_')) . '_title');
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function engineeringWorks()
    {
        $this->twig->load('services/engineering-works.twig');
        echo $this->twig->render('services/engineering-works.twig', $this->data);
    }



    /**
     * @return bool
     * @throws ExceptionSafeMySQL
     */
    protected function verification(): bool
    {
        if ($this->session_id && $this->access_token){
            $session = $this->customer->getSession($this->session_id);
            if (!is_array($session)) return false;

            $this->setCustomerId((int)$session['customer_id']);

            if (
                Cookie::get('_access_token', false) == $session['access_token'] &&
                Cookie::get('_session_id', false) == $session['id'] &&
                $this->userAgentMd5() == $session['hash_user_agent'])
            {
                $this->customer->setExpires($this->session_id, 3600);
                return true;
            }
        }
        return false;
    }



    /**
     * @return string md5
     */
    protected function userAgentMd5(): string
    {
        return md5(Header::userAgent());
    }



    /**
     * Identity Initialization
     */
    private function initializingDataToVerify()
    {
        $this->session_id = Cookie::get('_session_id', false);
        $this->access_token = Cookie::get('_access_token', false);
    }




    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }



    /**
     * @param mixed $customer_id
     */
    public function setCustomerId($customer_id):void
    {
        $this->customer_id = $customer_id;
    }



    /**
     * @return string
     */
    public function getSessionId():string
    {
        return $this->session_id;
    }




}