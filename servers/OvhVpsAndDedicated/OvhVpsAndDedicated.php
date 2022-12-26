<?php

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\AppContext;

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

function OvhVpsAndDedicated_CreateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}


function OvhVpsAndDedicated_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_UsageUpdate(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_ConfigOptions(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}


function OvhVpsAndDedicated_MetaData($params = null)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_ClientArea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp('clientarea', $params);
}

function OvhVpsAndDedicated_AdminServicesTabFields($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';


    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_Renew($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);

}


