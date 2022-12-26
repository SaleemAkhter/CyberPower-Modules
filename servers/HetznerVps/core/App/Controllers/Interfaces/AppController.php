<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces;

interface AppController
{
    public function getControllerInstanceClass($callerName, $params);
}
