<?php

if (!class_exists('\ModulesGarden\WordpressManager\Core\HandlerError\WhmcsErrorManagerWrapper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'HandlerError' . DIRECTORY_SEPARATOR . 'WhmcsErrorManagerWrapper.php';
}

\ModulesGarden\WordpressManager\Core\HandlerError\WhmcsErrorManagerWrapper::setErrorManager($errMgmt);
