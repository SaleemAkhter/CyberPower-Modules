<?php

if (!class_exists('\ModulesGarden\DirectAdminExtended\Core\HandlerError\WhmcsErrorManagerWrapper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'HandlerError' . DIRECTORY_SEPARATOR . 'WhmcsErrorManagerWrapper.php';
}

\ModulesGarden\DirectAdminExtended\Core\HandlerError\WhmcsErrorManagerWrapper::setErrorManager($errMgmt);
