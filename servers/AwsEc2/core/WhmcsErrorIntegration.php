<?php

if (!class_exists('\ModulesGarden\Servers\AwsEc2\Core\HandlerError\WhmcsErrorManagerWrapper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'HandlerError' . DIRECTORY_SEPARATOR . 'WhmcsErrorManagerWrapper.php';
}

\ModulesGarden\Servers\AwsEc2\Core\HandlerError\WhmcsErrorManagerWrapper::setErrorManager($errMgmt);
