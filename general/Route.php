<?php

use Engine\Core\Router\Route;
use Engine\Helper\HelperDI;

$route = new Route(HelperDI::di());


$route->get('/general/language.getPackage', 'LanguagePackageController:getPackage', 'DATA');

