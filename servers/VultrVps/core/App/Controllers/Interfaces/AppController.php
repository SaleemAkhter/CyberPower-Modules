<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Controllers\Interfaces;

interface AppController
{
    public function getControllerInstanceClass($callerName, $params);
}
