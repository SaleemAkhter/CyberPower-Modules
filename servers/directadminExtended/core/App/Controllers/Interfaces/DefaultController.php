<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Interfaces;

interface DefaultController
{
    public function execute($params = null);

    public function runExecuteProcess($params = null);
}
