<?php
ini_set('session.use_strict_mode', 1);
ini_set("display_errors",'On');
error_reporting(E_ALL & ~E_NOTICE);


//if (!defined('ROOT_DIR')) {define('ROOT_DIR', __DIR__);}
if (!defined('REMOTE_ADDR')) {define('REMOTE_ADDRESS', $_SERVER["REMOTE_ADDR"]);}

define('ENV', 'Cms');
define('DS', '/');



require_once 'engine/bootstrap.php';

