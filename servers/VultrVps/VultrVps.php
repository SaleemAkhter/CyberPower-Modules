<?php

use ModulesGarden\Servers\VultrVps\Core\App\AppContext;

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';





function VultrVps_CreateAccount(array $params = [])
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    

    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    

    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_ConfigOptions(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_AdminServicesTabFields(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_MetaData($params = [])
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function VultrVps_ClientArea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp('clientarea', $params);
}

function VultrVps_UsageUpdate(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();
    $appContext->runApp(__FUNCTION__, $params);
}
