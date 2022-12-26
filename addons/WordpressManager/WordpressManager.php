<?php

use \ModulesGarden\WordpressManager\Core\App\AppContext;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'WhmcsErrorIntegration.php';




function WordpressManager_config()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function WordpressManager_activate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function WordpressManager_deactivate()
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__);
}

function WordpressManager_upgrade($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    return $appContext->runApp(__FUNCTION__, $params);
}

function WordpressManager_output($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    

    $html = $appContext->runApp(__FUNCTION__, $params);

    if ($html)
    {
        echo $html;
    }
}

function WordpressManager_clientarea($params)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';
    $appContext = new AppContext();

    

    return $appContext->runApp(__FUNCTION__, $params);
}
