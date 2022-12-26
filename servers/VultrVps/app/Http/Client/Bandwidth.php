<?php


namespace ModulesGarden\Servers\VultrVps\App\Http\Client;


use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Pages\BackupDataTable;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Bandwidth\Pages\BandwidthGraph;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractClientController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;
use function ModulesGarden\Servers\VultrVps\Core\Helper\view;

class Bandwidth extends AbstractClientController
{
    use WhmcsParams;
    use ProductSetting;

    public function index()
    {
        $this->productSetting()->hasPermissionOrFail("bandwidth");
        sl("sidebar")->getSidebar("managementVultrVps")->getChild("bandwidth")->setActive(true);
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {
            return;
        }
        $view = view();
        $view->initCustomAssetFiles();
        return $view->addElement(BandwidthGraph::class);
    }
}