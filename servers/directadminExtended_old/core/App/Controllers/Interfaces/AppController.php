<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Interfaces;

interface AppController
{
    public function getControllerInstanceClass($callerName, $params);
}
