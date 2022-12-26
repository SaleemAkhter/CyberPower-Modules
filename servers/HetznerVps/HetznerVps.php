<?php

use ModulesGarden\Servers\HetznerVps\App\Helpers;
use ModulesGarden\Servers\HetznerVps\App\Libs\Metrics\MyMetricsProvider;
use ModulesGarden\Servers\HetznerVps\App\Models\Accounts;
use ModulesGarden\Servers\HetznerVps\Core\App\AppContext;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
require_once "core" . DS . "Bootstrap.php";

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function HetznerVps_MetaData()
{
    return array(
        'DisplayName' => 'Hetzner VPS',
        'RequiresServer' => true,
        'ListAccountsUniqueIdentifierDisplayName' => 'Domain',
        'ListAccountsUniqueIdentifierField' => 'domain',

        'ListAccountsProductField' => 'configoption1',
    );
}

function HetznerVps_ListAccounts(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_ConfigOptions($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_TestConnection(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_CreateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_SuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_UnsuspendAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_TerminateAccount(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_ChangePackage(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_AdminServicesTabFields(array $params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function HetznerVps_ClientArea($params)
{
    if ($params['status'] != 'Active') {
        return;
    }

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

    

    $appContext = new AppContext();

    return $appContext->runApp('clientarea', $params);
}

function HetznerVps_MetricProvider($params)
{
    return new MyMetricsProvider($params);
}

function HetznerVps_UsageUpdate($params)
{
    try {
        $action = new Helpers\UsageUpdate($params);
        $action->update();
    } catch (\Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}
