<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Jobs;

use ModulesGarden\Servers\DirectAdminExtended\Core\CommandLine\Job;

class MyTestJob extends Job
{
    public function handle($text)
    {
        throw new \Exception('XXXX');
    }
}