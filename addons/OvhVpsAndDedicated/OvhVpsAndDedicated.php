<?php

use \ModulesGarden\OvhVpsAndDedicated\Core\App\AppContext;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';

function OvhVpsAndDedicated_config()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function OvhVpsAndDedicated_activate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function OvhVpsAndDedicated_deactivate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function OvhVpsAndDedicated_upgrade($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function OvhVpsAndDedicated_output($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    $html = $appContext->runApp(__FUNCTION__, $params);

    if ($html)
    {
        echo $html;
    }
}

