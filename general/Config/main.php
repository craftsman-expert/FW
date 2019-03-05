<?php
return [
    'HTTP_CATALOG'      => 'http://russiaintouch.com',
    'DEFAULTLANG'       => 'russian',
    'defaultTimezone'   => 'America/Chicago',

    'THEME'             => 'stack',
    'DIR_IMAGE'         => 'content/images',
    'DIR_IMG_MINIATURES'=> '/content/img/miniatures' . DS . strtolower(ENV),


    /*************************************
     *              CMS
     *************************************/
    'CMS_DEFAULT_LANG'  => 'zh',


    /*************************************
     *              Twig
     ************************************/
    'TWIG_CACHE'        => '/twig/cache'
];