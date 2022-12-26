<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces;

interface AppController
{
    public function getControllerInstanceClass($callerName, $params);
}
