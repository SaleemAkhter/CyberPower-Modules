<?php

if (!class_exists('\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\WhmcsErrorManagerWrapper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'HandlerError' . DIRECTORY_SEPARATOR . 'WhmcsErrorManagerWrapper.php';
}

\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\WhmcsErrorManagerWrapper::setErrorManager($errMgmt);
