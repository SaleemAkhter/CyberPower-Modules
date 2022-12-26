<?php

namespace ModulesGarden\DirectAdminExtended\App\Jobs;

use ModulesGarden\DirectAdminExtended\Core\CommandLine\Job;

class MyTestJob extends Job
{
    public function handle($text)
    {
        throw new \Exception('XXXX');
    }
}