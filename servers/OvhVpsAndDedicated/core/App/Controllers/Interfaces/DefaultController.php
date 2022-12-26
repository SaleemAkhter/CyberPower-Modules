<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces;

interface DefaultController
{
    public function execute($params = null);
    
    public function runExecuteProcess($params = null);
}
