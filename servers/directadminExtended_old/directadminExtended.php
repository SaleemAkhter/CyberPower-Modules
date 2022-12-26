<?php

use \ModulesGarden\Servers\DirectAdminExtended\Core\App\AppContext;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;



if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

function DirectAdminExtended_CreateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_ChangePassword(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_UsageUpdate(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_ConfigOptions(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_MetaData()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    
    $appContext = new AppContext();    
    
    return $appContext->runApp(__FUNCTION__, []);
}

function DirectAdminExtended_ClientArea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    
    
    $appContext = new AppContext();    
    
    return $appContext->runApp('clientarea', $params);
}

function DirectAdminExtended_LoginLink($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_AdminLink($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_ListAccounts($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_MetricProvider($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}