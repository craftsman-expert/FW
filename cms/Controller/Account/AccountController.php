<?php

namespace Cms\Controller\Account;


use Cookie;
use Engine\AbstractController;
use Engine\DI\DI;
use Engine\Helper\Header;
use Exception;

/**
 * Class AccountController
 * @package Cms\Controller\Account
 */
class AccountController extends AbstractController
{
    /**
     * AccountController constructor.
     *
     * @param DI $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->lpm->load('main', $this->lang, ENV);

    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sigIn()
    {
        $this->data['user_agent_md5'] = md5(Header::userAgent());

        $this->twig->load('account/sigIn.twig');
        echo $this->twig->render('account/sigIn.twig', $this->data);
    }

}