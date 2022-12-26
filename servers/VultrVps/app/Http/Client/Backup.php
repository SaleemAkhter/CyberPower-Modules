<?php


namespace ModulesGarden\Servers\VultrVps\App\Http\Client;


use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Pages\BackupDataTable;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Sections\BackupAlertSection;
use ModulesGarden\Servers\VultrVps\Core\Http\AbstractClientController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;
use function ModulesGarden\Servers\VultrVps\Core\Helper\view;

class Backup extends AbstractClientController
{
    use WhmcsParams;
    use ProductSetting;

    public function index()
    {
        $this->productSetting()->hasPermissionOrFail("backup");
        sl("sidebar")->getSidebar("managementVultrVps")->getChild("backup")->setActive(true);
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {
            return;
        }
        $view = view();
        $instance = (new InstanceFactory())->fromWhmcsParams();
        $dataTable = new BackupDataTable();
        if(!$instance->hasAutomaticBackups()){
            $view->addElement(new BackupAlertSection());
            $dataTable->setAutomaticBackups(false);
        }
        return $view->addElement( $dataTable);
    }
}