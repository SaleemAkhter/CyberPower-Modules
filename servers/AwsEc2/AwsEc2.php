<?php

use \ModulesGarden\Servers\AwsEc2\Core\App\AppContext;

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';





function AwsEc2_CreateAccount(array $params = [])
{

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    

    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    

    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_ConfigOptions(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_AdminServicesTabFields(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_MetaData($params = [])
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function AwsEc2_ClientArea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp('clientarea', $params);
}
