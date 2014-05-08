<?php

error_reporting(-1);
ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);

define('ROOT_PATH',        __DIR__ . DS);
define('APPLICATION_PATH', ROOT_PATH . 'Application' . DS);
define('CONFIG_PATH',      APPLICATION_PATH . 'configs' . DS);

define('PUBLIC_PATH', ROOT_PATH . 'www' . DS);
define('FILE_PATH',   PUBLIC_PATH . 'files' . DS);

define('FILE_PATH_URL',   '/files/');

require_once ROOT_PATH . 'vendor' . DS . 'autoload.php';

use Application\Command\PullrequestCommand;
use Nameless\Core\Kernel;
use Nameless\Core\Console;
use Application\Command\AssetsCommand;

$console = new Console(new Kernel(), 'Nameless', '0.2.0');

$assets = new AssetsCommand();
$assets->setApplication($console);

$pull_requests = new PullrequestCommand();
$pull_requests->setApplication($console);

$console->add($assets);
$console->add($pull_requests);
$console->run();