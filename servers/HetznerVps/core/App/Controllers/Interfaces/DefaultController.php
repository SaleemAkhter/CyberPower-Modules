<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces;

interface DefaultController
{
    public function execute($params = null);

    public function runExecuteProcess($params = null);
}
