<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Modules\Directadmin;
use function ModulesGarden\WordpressManager\Core\Helper\fire;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use ModulesGarden\WordpressManager\Core\Helper\RandomStringGenerator;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class AutoInstallPluginPackagesJob extends AutoInstallJob
{
    public function handle($text)
    {
        $this->installation = Installation::findOrFail($this->getModelData()['installationId']);
        $names = [];
        $query = PluginPackage::ofId( $this->getModelData()['pluginPackages'])->enable();
        /**
         * @var $package PluginPackage
         */
        foreach ($query->get() as $package)
        {
            $names[] = sprintf("#%s %s", $package->id, $package->name);
            foreach ($package->items as $item)
            {
                /* @var $item main\App\Models\PluginPackageItem */
                $this->subModule()->pluginInstall($this->getInstallation(), $item->slug);
                sleep(4);
                $this->subModule()->pluginActivate($this->getInstallation(), $item->slug);
            }
        }
        infoLog(sprintf("Plugin Packages %s have been  installed. Installation ID #%s, Client ID #%s, Hosting ID #%s", implode(", ", $names), $this->getInstallation()->id, $this->getInstallation()->user_id, $this->getInstallation()->hosting_id));
    }



}
