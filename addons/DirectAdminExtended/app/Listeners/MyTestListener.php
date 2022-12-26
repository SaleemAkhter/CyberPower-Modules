<?php

namespace ModulesGarden\DirectAdminExtended\App\Listeners;

use ModulesGarden\DirectAdminExtended\App\Events\MyTestEvent;
use ModulesGarden\DirectAdminExtended\Core\Queue\Job;

class MyTestListener extends Job
{
    public function handle(MyTestEvent $event)
    {
        return [
            'XXX'   => 1
        ];
    }
}