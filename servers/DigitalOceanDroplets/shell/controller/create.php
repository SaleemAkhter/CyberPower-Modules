<?php

define('DS', DIRECTORY_SEPARATOR);
$modulePath = dirname(dirname(__DIR__));
$whmcsPath  = substr(__DIR__, 0, strpos(__DIR__, 'modules' . DS . 'servers' . DS));

require_once dirname(__DIR__) . DS . 'ProgressBar' . DS . 'Progress.php';
require_once __DIR__ . DS . 'Model' . DS . 'AbstractControllerModel.php';

$readData = $argv ? $argv : $_SERVER['argv'];

$name = "";
$isAdmin = false;

foreach ($readData as $key => $string)
{
    if ('--name' === $string)
    {
        $name = $readData[$key + 1];
    }
    elseif('--isAdmin')
    {
        $isAdmin = true;
    }
}

$controller = new AbstractControllerModel();


if ($name === "" || $name === null)
{
    $name = "Home";
}

$massage = $controller
        ->setName($name)
        ->setIsAdmin($isAdmin)
        ->renderFile($modulePath . DS . 'app' . DS . 'Http' . DS);

$massage = "\n\n\033[32m" . $massage . "\n\n\033[0m";
print $massage;