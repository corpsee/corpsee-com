<?php

error_reporting(-1);
ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);

define('ROOT_PATH', __DIR__ . '/');
define('APPLICATION_PATH', ROOT_PATH . 'Application/');
define('CONFIG_PATH', APPLICATION_PATH . 'configs/');

define('PUBLIC_PATH', ROOT_PATH . 'www/');
define('FILE_PATH', PUBLIC_PATH . 'files/');

define('FILE_PATH_URL',   '/files/');

require_once ROOT_PATH . 'vendor/autoload.php';

use Application\Command\PullrequestCommand;
use Nameless\Core\Application;
use Nameless\Core\Console;
use Application\Command\AssetsCommand;

$console = new Console(new Application(), 'corpsee.com', 'v17');

$assets = new AssetsCommand();
$assets->setApplication($console);

$pull_requests = new PullrequestCommand();
$pull_requests->setApplication($console);

$console->add($assets);
$console->add($pull_requests);
$console->run();