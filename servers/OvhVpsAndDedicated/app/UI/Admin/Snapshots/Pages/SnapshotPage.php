<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Pages;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Snapshot\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons\Edit;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons\Restore;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons\Create;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons\Delete;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SnapshotPage extends DataTable implements ClientArea, AdminArea
{
    use WhmcsParamsApp;

    protected $id         = 'snapshotPage';
    protected $name       = 'snapshotPage';
    protected $title      = 'snapshotPage';
    protected $searchable = false;
    protected $datatableData = [];

    public function getNamespace()
    {
        return str_replace('\\', '_', get_class($this));
//        return 'ModulesGarden_Servers_OvhVpsAndDedicated\App\UI\Admin\Snapshots\Pages\SnapshotPage';
    }

    public function loadHtml()
    {
        $this->addColumn((new Column('creationDate'))->setOrderable(false)->setSearchable(false))
             ->addColumn((new Column('description'))->setOrderable(false)->setSearchable(false));
        $this->disabledViewFooter();
        $this->disabledViewTopBody();
    }

    public function initContent()
    {
        $this->setDataTableData();


        if(!$this->datatableData || empty($this->datatableData))
        {
            $this->addTitleButton(new Create());
            return;
        }
//
        $this->addActionButton(new Edit());
        $this->addActionButton(new Restore());
        $this->addActionButton(new Delete());
    }

    /**
     * @param $key
     * @param $row
     * @return string
     */
    public function replaceFieldCreationDate($key, $row)
    {
        $date = new \DateTime($row['creationDate']);

        return $date->format('Y-m-d H:i:s');
    }

    protected function loadData()
    {
        $data      = [];
        if(!empty($this->datatableData))
        {
            $data[] = $this->datatableData;;
        }



        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

    protected function setDataTableData()
    {
        $dataManger = new Repository(false, $this->getWhmcsEssentialParams());

        $data = $dataManger->get()->model();

        if($data)
        {
            $data = $data->toArray();
        }
        else
        {
            $data = [];
        }

        $this->datatableData = $data;
    }
}
