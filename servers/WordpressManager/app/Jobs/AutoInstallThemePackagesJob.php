<?php


namespace ModulesGarden\WordpressManager\App\Jobs;

use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Models\ThemePackage;
use ModulesGarden\WordpressManager\App\Models\ThemePackageItem;
use ModulesGarden\WordpressManager\App\Modules\Directadmin;
use function ModulesGarden\WordpressManager\Core\Helper\fire;
use function ModulesGarden\WordpressManager\Core\Helper\queue;
use ModulesGarden\WordpressManager\Core\Helper\RandomStringGenerator;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\infoLog;

class AutoInstallThemePackagesJob extends AutoInstallJob
{
    public function handle($text)
    {
        $this->installation = Installation::findOrFail($this->getModelData()['installationId']);
        $names = [];
        $query = ThemePackage::ofId( $this->getModelData()['themePackages'])->enable();
        /**
         * @var $package ThemePackage
         */
        foreach ($query->get() as $package)
        {
            $names[] = sprintf("#%s %s", $package->id, $package->name);
            foreach ($package->items as $item)
            {
                /**
                 * @var ThemePackageItem $item ,
                 */
                $this->subModule()->getTheme($this->getInstallation())->install($item->slug);
            }
        }
        if($this->installation->hosting->productSettings->getDefaultTheme()){
            $this->sleep(1);
            $this->subModule()->getTheme($this->getInstallation())->activate($this->installation->hosting->productSettings->getDefaultTheme());
        }
        infoLog(sprintf("Theme Packages %s have been  installed. Installation ID #%s, Client ID #%s, Hosting ID #%s", implode(", ", $names), $this->getInstallation()->id, $this->getInstallation()->user_id, $this->getInstallation()->hosting_id));
    }



}
