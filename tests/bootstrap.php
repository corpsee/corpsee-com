<?php

error_reporting(-1);
ini_set('display_errors', 1);

define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

define('ROOT_PATH', dirname(__DIR__) . '/');
define('APPLICATION_PATH', ROOT_PATH . 'src/');
define('CONFIG_PATH', APPLICATION_PATH . 'configs/');

define('PUBLIC_PATH', ROOT_PATH . 'www/');
define('FILE_PATH', PUBLIC_PATH . 'files/');

define('FILE_PATH_URL', '/files/');

define('POSTGRES', 'Y-m-d H:i:sP');

require_once ROOT_PATH . 'vendor/autoload.php';
