<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Pages;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Column;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SnapshotList extends DataTable implements ClientArea, AdminArea {

    protected $id    = 'snapshotsTable';
    protected $name  = 'snapshotsTable';
    protected $title = 'snapshots';

    public function loadHtml() {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('date'))->setOrderable('DESC')->setSearchable(true));
    }

    public function initContent() {
        $this->addMassActionButton(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons\MassAction\DeleteSnapshots());
        $this->addActionButton(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons\Restore());
        $this->addActionButton(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons\Delete());
        $this->addButton(new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons\Create());
    }

    protected function loadData() {
        $dataManger   = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Helpers\SnapshotManager();
        $data         = $dataManger->getSnapshotsToTable();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('date', 'desc');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
