<?php

namespace Engine\Service\Twig;

use Engine\Core\Config\Config;
use Engine\Core\LPManager\LPManager;
use Engine\Helper\Cookie;
use Engine\Helper\PHPOptions;
use Engine\Service\AbstractProvider;
use Exception;
use Lang;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Function;
use Twig_Loader_Filesystem;

class Provider extends AbstractProvider
{
    const ADMIN_THEME = '%s/admin/View/';
    const CMS_THEME = '%s/content/themes/%s/twg/';

    /**
     * @return mixed
     * @throws Exception
     */
    function init ()
    {

        switch (ENV){
            case 'Admin': $paths =  sprintf(self::ADMIN_THEME, ROOT_DIR);
                break;
            case 'Cms': $paths = sprintf(self::CMS_THEME, ROOT_DIR, Config::item('THEME'));
                break;
            default: return;
        }


        $loader  = new Twig_Loader_Filesystem($paths);
        $twgEnv  = new Twig_Environment($loader, array(
            'debug' => true,
            //'cache' => PHPOptions::getTempDir() . Config::item('TWIG_CACHE'),
        ));

        $twgEnv->addFunction(
            new Twig_Function('translate', function ($package, $key) {return $this->di->get('lpm')->translate($package, $key);}

        ));
        $twgEnv->addFunction(new Twig_Function('md5', function ($val) {return md5($val);}));
        $twgEnv->addFunction(new Twig_Function('date', function ($format) {return date($format);}));
        $twgEnv->addFunction(new Twig_Function('base64_encode', function ($data) {return base64_encode($data);}));
        $twgEnv->addFunction(new Twig_Function('base64_decode', function ($data) {return base64_decode($data);}));
        $twgEnv->addExtension(new Twig_Extension_Debug());

        $this->di->set('twig', $twgEnv);
    }


}