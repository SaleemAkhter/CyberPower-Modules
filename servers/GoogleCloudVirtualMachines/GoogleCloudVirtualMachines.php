<?php

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\AppContext;

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';





function GoogleCloudVirtualMachines_CreateAccount(array $params = [])
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
   
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    

    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    

    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_ConfigOptions(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_AdminServicesTabFields(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_MetaData($params = [])
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function GoogleCloudVirtualMachines_ClientArea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp('clientarea', $params);
}
