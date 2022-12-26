<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Tasks\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class TaskList extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'taskTable';
    protected $name  = 'taskTable';
    protected $title = 'taskTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('task'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('status'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('created_at'))->setOrderable('DESC')->setSearchable(true))
                ->addColumn((new Column('end_at'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        
    }

    protected function loadData()
    {
        $dataProvider = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider();
        $tasks = new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Tasks\Helpers\TaskTable($this->whmcsParams);
        $dataProvider->setData($tasks->getTasks());
        $dataProvider->setDefaultSorting('created_at', 'desc');
        $this->setDataProvider($dataProvider);
    }

}
