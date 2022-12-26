<?php

if (!class_exists('\ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\WhmcsErrorManagerWrapper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'HandlerError' . DIRECTORY_SEPARATOR . 'WhmcsErrorManagerWrapper.php';
}

\ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\WhmcsErrorManagerWrapper::setErrorManager($errMgmt);
