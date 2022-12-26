<?php

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;
use \ModulesGarden\Servers\DigitalOceanDroplets\App\Models\Accounts;

if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}
require_once "core" . DS . "Bootstrap.php";

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

function DigitalOceanDroplets_MetaData()
{
    return array(
        'DisplayName'    => 'DigitalOcean Droplets',
        'RequiresServer' => true,
    );
}

function DigitalOceanDroplets_ConfigOptions($params)
{
    if ($_REQUEST['action'] != 'save')
    {
        
        $configOption = new Helpers\ConfigOptions();
        return $configOption->getConfig();
    }
}

function DigitalOceanDroplets_TestConnection(array $params)
{
    try
    {
        $connectionTester = new Helpers\TestConnection($params);
        $connectionTester->testConnection();

        return ['success' => true];
    }
    catch (\Exception $exc)
    {
        return ['error' => $exc->getMessage()];
    }
}

function DigitalOceanDroplets_CreateAccount(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->create();
}

function DigitalOceanDroplets_SuspendAccount(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->suspendAccount();
}

function DigitalOceanDroplets_UnsuspendAccount(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->unsuspendAccount();
}

function DigitalOceanDroplets_TerminateAccount(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->terminateAccount();
}

function DigitalOceanDroplets_ChangePassword(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->resetPassword();
}

function DigitalOceanDroplets_ChangePackage(array $params)
{
    
    $action = new Helpers\AccoutnActions($params);
    return $action->changePackage();
}

function DigitalOceanDroplets_ClientArea($params)
{

    try
    {
        $serviceManager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager($params);
        $serviceManager->getVM();
        if (class_exists('\ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\ClientPageControler'))
        {
            $pageControler = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\ClientPageControler($params);
            $resault       = $pageControler->loadPage();
        }
        return $resault;
    }
    catch (\Exception $exc)
    {
        $exceptionToken = md5(time());
        logModuleCall('DigitalOceanDroplets - ' . $exceptionToken, __FUNCTION__, ['error' => $exc->getMessage(), 'trace' => $exc->getTraceAsString()], $params);

        return [
            'tabOverviewReplacementTemplate' => 'templates/client/errors/error.tpl',
            'templateVariables'              => ['errorMessage' => $exc->getMessage()]
        ];
    }
}

function DigitalOceanDroplets_AdminServicesTabFields($params)
{
    try
    {
        $serviceManager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager($params);
        $serviceManager->getVM();
        return ['' => \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl('adminProductPage')->setParams($params)->execute()];
    }
    catch (Exception $ex)
    {
        
    }
}

