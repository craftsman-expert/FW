<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ENV', 'General');
define('DS', '/');

require_once '../engine/bootstrap.php';