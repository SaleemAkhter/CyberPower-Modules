<?php


namespace ModulesGarden\Servers\VultrVps\App\Http\Client;


use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\App\UI\Client\ChangeOs\Pages\ChangeOsDataTable;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Pages\SnapshotDataTable;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractClientController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;
use function ModulesGarden\Servers\VultrVps\Core\Helper\view;

class ChangeOs extends AbstractClientController
{
    use WhmcsParams;
    use ProductSetting;

    public function index()
    {
        $this->productSetting()->hasPermissionOrFail("changeOs");
        sl("sidebar")->getSidebar("managementVultrVps")->getChild("changeOs")->setActive(true);
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {
            return;
        }
        return view()->addElement(ChangeOsDataTable::class);
    }
}