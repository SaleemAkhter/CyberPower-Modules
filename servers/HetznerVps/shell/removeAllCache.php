<?php

define('DS', DIRECTORY_SEPARATOR);
$modulePath = dirname(__DIR__);
$whmcsPath  = substr(__DIR__, 0, strpos(__DIR__, 'modules' . DS . 'servers' . DS));

require_once $whmcsPath . DS . 'init.php';
require_once $modulePath . DS . 'core' . DS . 'Bootstrap.php';
require_once __DIR__ . DS . 'ProgressBar' . DS . 'Progress.php';

use \ModulesGarden\Servers\HetznerVps\Core\Cache\CacheManager;

$cache = new CacheManager();
$cache->clearAll();
echo "\n \033[31m Clear all cache files.\n\n";
