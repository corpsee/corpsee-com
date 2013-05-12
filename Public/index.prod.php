<?php

error_reporting(E_ALL ^ (E_STRICT | E_NOTICE | E_DEPRECATED));
ini_set('display_errors', 0);

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

use Framework\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Framework\Kernel;

$options = array
(
	'debug'                  => FALSE,
	'default_ttl'            => 0,
	'private_headers'        => array('Authorization', 'Cookie'),
	'allow_reload'           => TRUE,
	'allow_revalidate'       => TRUE,
	'stale_while_revalidate' => 2,
	'stale_if_error'         => 60,
);

$framework = new Kernel();
$framework = new HttpCache(new Kernel(), new Store(ROOT_PATH . 'Cache'), NULL, $options);