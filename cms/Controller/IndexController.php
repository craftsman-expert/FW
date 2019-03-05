<?php


namespace Cms\Controller;


use Config;
use Engine\DI\DI;
use Engine\Helper\Header;
use Engine\Helper\Network;
use Exception;
use General\Model\Language\Language;
use GuzzleHttp\Client;

/**
 * Class IndexController
 * @property mixed model
 * @package Cms\Controller
 */
class IndexController extends CmsController
{
    /**
     * @var array
     */
    protected $data = [];
    /** @var int */
    public $user_group_id;
    /** @var int */
    public $language_code;
    /**
     * @var Language
     */
    public $language;



    /**
     * IndexController constructor.
     *
     * @param DI $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
    }



    /**
     * @throws \Exception
     */
    public function index()
    {
        Network::location('/page/product.list');
    }
}