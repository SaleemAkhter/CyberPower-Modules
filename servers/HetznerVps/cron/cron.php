<?php

define('DS', DIRECTORY_SEPARATOR);
$modulePath = dirname(__DIR__);
$whmcsPath  = dirname(dirname(dirname($modulePath)));

require_once $whmcsPath . DS . 'init.php';
require_once $modulePath . DS . 'core' . DS . 'Bootstrap.php';

//cause WHMCS
ini_set('max_execution_time', 0);

$defaultParamsList = [
    'DemonTask'
];

$argList = $argv ? $argv : $_SERVER['argv'];
if (count($argList) === 0)
{
    $argList = [__FILE__];
}
if (count($argList) === 1)
{
    $argList = array_merge($argList, $defaultParamsList);
}

(new \ModulesGarden\Servers\HetznerVps\App\Cron\WithoutThread\Tasks())->run();

//\ModulesGarden\Servers\HetznerVps\Core\ServiceLocator::call(\ModulesGarden\Servers\HetznerVps\Core\CommandLine\CronManager::class)
//        ->setArgv($argList)
//        ->execute();

