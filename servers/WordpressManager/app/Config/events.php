<?php

return [
    \ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent::class => [
        ModulesGarden\WordpressManager\App\Listeners\InstallationCreatedListener::class
    ],
];
