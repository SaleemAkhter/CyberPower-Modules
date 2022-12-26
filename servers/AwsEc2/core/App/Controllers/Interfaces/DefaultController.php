<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Interfaces;

interface DefaultController
{
    public function execute($params = null);

    public function runExecuteProcess($params = null);
}
