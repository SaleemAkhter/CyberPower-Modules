<?php

use \ModulesGarden\DirectAdminExtended\Core\App\AppContext;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';


function DirectAdminExtended_config()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function DirectAdminExtended_activate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function DirectAdminExtended_deactivate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function DirectAdminExtended_upgrade($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function DirectAdminExtended_output($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    $html = $appContext->runApp(__FUNCTION__, $params);

    if ($html)
    {
        echo $html;
    }
}

function DirectAdminExtended_clientarea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}
