<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Listeners;

use ModulesGarden\Servers\DirectAdminExtended\App\Events\MyTestEvent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Queue\Job;

class MyTestListener extends Job
{
    public function handle(MyTestEvent $event)
    {
        return [
            'XXX'   => 1
        ];
    }
}