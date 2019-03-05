<?php

namespace Engine;


use Engine\Core\Config\Config;
use Engine\Core\Database\SafeMySQL;
use Engine\Core\LPManager\LPManager;
use Engine\Core\Request\Request;
use Engine\Core\Setting\Setting;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Cookie;
use Engine\Helper\IDGenerator;
use Engine\Helper\Lang;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Twig_Environment;


/**
 * Class Controller
 * @package Engine
 */
abstract class AbstractController
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var LPManager
     */
    protected $lpm;

    /**
     * Language localization
     * @var string
     */
    protected $lang;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Load
     * @throws Exception
     */
    protected $load;
    /**
     * @var \Engine\Core\Plugin\Plugin
     */
    protected $plugin;

    /**
     * @var Setting
     */
    protected $setting;
    /**
     * @var SafeMySQL
     */
    protected $db;
    /**
     * @var Client
     */
    protected $http;
    /**
     * application session id
     * @var string
     */
    private $app_session_id;
    /**
     * @var \Nexmo\Client
     */
    protected $nexmo_message_client;



    /**
     * Controller constructor.
     * @param DI $di
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        Cookie::set('XDEBUG_SESSION', 'XDEBUG_ECLIPSE');
        $this->lang = Cookie::get('lang', Config::item('DEFAULT_LANGUAGE'));

        $this->di = $di;
        $this->config = $this->di->get('config');
        $this->db = $this->di->get('db');
        $this->setting = $this->di->get('setting');
        $this->request = $this->di->get('request');
        $this->load = $this->di->get('load');
        $this->twig = $this->di->get('twig');
        $this->lpm = $this->di->get('lpm');
        $this->http = $this->di->get('http');
        $this->nexmo_message_client = $this->di->get('nexmo_message_client');

        $this->setAppSessionId(Cookie::get('APP_SESSION', IDGenerator::generateID('',true))) ;
        $this->data['URL_PATH'] = Common::getPathUrl();
    }



    /**
     * @return SafeMySQL
     */
    public function db():SafeMySQL
    {
        return $this->db;
    }



    /**
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }



    /**
     * @param array $data
     */
    public function setData(array $data):void
    {
        $this->data = $data;
    }



    /**
     * @return string
     */
    public function getAppSessionId():string
    {
        return $this->app_session_id;
    }



    /**
     * @param string $app_session_id
     */
    public function setAppSessionId(string $app_session_id):void
    {
        $this->app_session_id = $app_session_id;
    }



    /**
     * @throws Exception
     */
    private function twigGlobalVarsInit()
    {
        $this->twig->addGlobal('NAV_BAR_TITLE', Config::item('NAV_BAR_TITLE'));
        $this->twig->addGlobal('HTTP_CATALOG', Config::item('HTTP_CATALOG'));
        $this->twig->addGlobal('DIR_IMAGES', sprintf('%s/%s', Config::item('HTTP_CATALOG'), Config::item('DIR_IMAGE')));
        $this->twig->addGlobal('DEFAULT_IMAGE', sprintf('%s', Config::item('DEFAULT_IMAGE')));

        $this->twig->addGlobal('languages', languages());
    }



    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->di->get($key);
    }



    /**
     * @return AbstractController
     */
    public function initVars()
    {
        $vars = array_keys(get_object_vars($this));
        foreach ($vars as $var) {
            if ($this->di->has($var)) {
                $this->{$var} = $this->di->get($var);
            }
        }
        return $this;
    }



    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }



    /**
     * @return Core\Plugin\Plugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }



}