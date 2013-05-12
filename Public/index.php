<?php

error_reporting(-1);
ini_set('display_errors', 1);

define('DS',        DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);

define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('TEMPLATE_PATH',    APPLICATION_PATH . 'Templates' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'Configs' . DS);

define('PUBLIC_PATH', ROOT_PATH . 'Public' . DS);
define('FILE_PATH',   PUBLIC_PATH . 'files' . DS);
define('STYLE_PATH',  FILE_PATH . 'styles' . DS);
define('SCRIPT_PATH', FILE_PATH . 'scripts' . DS);

define('FILE_PATH_URL',   '/files/');
define('ICON_PATH_URL',   FILE_PATH_URL . 'icons/');
define('STYLE_PATH_URL',  FILE_PATH_URL . 'styles/');
define('SCRIPT_PATH_URL', FILE_PATH_URL . 'scripts/');

require_once ROOT_PATH . '../nameless.protected/Vendors/autoload.php';

use Framework\Kernel;

$framework = new Kernel();
$framework->run();