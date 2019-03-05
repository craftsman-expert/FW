<?php


namespace General\Controller;

use Engine\Core\Exception\ExceptionCMS;
use Engine\Core\LPManager\LPManager;
use Engine\DI\DI;
use Engine\Helper\Header;
use Engine\Helper\Helper;
use Engine\Helper\Message;
use Engine\Helper\PHPOptions;
use Exception;
use stdClass;

/**
 * Class LanguagePackageController
 * @package General\Controller
 */
class LanguagePackageController extends GeneralController
{

    /**
     * @var LPManager
     */
    private $lp_manager;

    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->lp_manager = $di->get('lpm');
    }



    /**
     * @throws Exception
     */
    public function getPackage()
    {
        $env = $this->request->mixed('env', 'Cms');
        $packages = $this->request->mixed('packages', false);
        $lang = $this->request->mixed('lang', $this->lang);

        if (!$packages){
            Message::error('', -1);
        }

        $langObj = new stdClass();
        $langObj->items = [];

        foreach (explode(',', $packages) as $item){
            $this->lp_manager->load($item, $lang, $env); //$this->load->language($item, $lang, $env);
        }

        $langObj->items = $this->lp_manager->getPackages();

        // header("Cache-control: public");
        // header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24) . " GMT");
        Helper::echoJsonUtf8($langObj);
        exit();
    }
}