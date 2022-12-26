<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Buttons\ChangeKernelButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Helpers\KernelManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class KernelManage extends DataTable implements ClientArea, AdminArea {

    protected $id    = 'kernelManageTable';
    protected $name  = 'kernelManageTable';
    protected $title = 'kernel';

    public function loadHtml() {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('name'))->setOrderable('DESC')->setSearchable(true));
    }

    public function initContent() {
        $this->addActionButton(new ChangeKernelButton());

    }

    protected function loadData() {

        $dataManager = new KernelManager($this->whmcsParams);
        $data         = $dataManager->getAvailableKernel();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('name', 'DESC');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
