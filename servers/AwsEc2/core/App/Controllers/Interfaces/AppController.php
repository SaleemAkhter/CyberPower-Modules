<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Interfaces;

interface AppController
{
    public function getControllerInstanceClass($callerName, $params);
}
