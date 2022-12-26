<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Pages;

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
class SnapshotList extends DataTable implements ClientArea, AdminArea {

    protected $id    = 'snapshotsTable';
    protected $name  = 'snapshotsTable';
    protected $title = 'snapshots';

    public function loadHtml() {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('name'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('date'))->setOrderable('DESC')->setSearchable(true));
    }

    public function initContent() {
        $this->addMassActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\MassAction\DeleteSnapshots());
        $this->addActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\Restore());
        $this->addActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\Delete());
        $this->addButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons\Create());
    }

    protected function loadData() {
        $dataManger   = new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Helpers\SnapshotManager($this->whmcsParams);
        $data         = $dataManger->getSnapshotsToTable();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('date', 'desc');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
