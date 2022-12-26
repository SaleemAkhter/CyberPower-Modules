<?php

namespace ModulesGarden\WordpressManager\App\Listeners;

use \ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Jobs\AutoInstallPluginPackagesJob;
use ModulesGarden\WordpressManager\App\Jobs\AutoInstallThemePackagesJob;
use ModulesGarden\WordpressManager\App\Modules\Plesk;
use function ModulesGarden\WordpressManager\Core\Helper\queue;

class InstallationCreatedListener
{
    public function handle(InstallationCreatedEvent $event)
    {
        /**
         * @var Plesk $module
         */
        $module = Wp::subModule($event->getInstallation()->hosting);
        if(method_exists($module, "onInstallationCreated")){
            $module->onInstallationCreated($event->getInstallation());
        }
        //Auto Install Plugin Packages
        $pluginPackages = $event->getInstallation()->hosting->productSettings->getAutoInstallPluginPackages();
        if($pluginPackages){
            queue(AutoInstallPluginPackagesJob::class,['installationId' => $event->getInstallation()->id, "pluginPackages" => $pluginPackages ]);
        }
        //Auto Install Theme Packages
        $themePackages = $event->getInstallation()->hosting->productSettings->getAutoInstallThemePackages();
        if($themePackages){
            queue(AutoInstallThemePackagesJob::class,['installationId' => $event->getInstallation()->id, "themePackages" => $themePackages ]);
        }
    }
} 