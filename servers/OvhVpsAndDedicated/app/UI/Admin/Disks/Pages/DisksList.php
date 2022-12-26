<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Pages;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Traits\Widgets\Datatable\DefaultValueOnEmptyColumn;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Helpers\Decorators;
use ModulesGarden\OvhVpsAndDedicated\App\Helpers\Formatter as StyleFormatter;


/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class DisksList extends DataTable implements ClientArea, AdminArea
{
    use DefaultValueOnEmptyColumn;

    protected $id    = 'diskTable';
    protected $name  = 'diskTable';
    protected $title = 'Disk';

    public function loadHtml()
    {
        $this->addColumn((new Column('id'))->setOrderable(DataProvider::SORT_DESC)->setSearchable(true))
            ->addColumn((new Column('type'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('size'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('bandwidthLimit'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('lowFreeSpaceThreshold'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('state'))->setOrderable()->setSearchable(true));

        $this->setDefaultValueForEveryColumn(true);
    }


    public function initContent()
    {
//        $this->addActionButton(new Edit());
//        $this->addButton(new Create());
    }

    /**
     *  Load Datatable records
     */
    protected function loadData()
    {
        $data = (new Disk())->getDisks();

        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $dataProvider->setDefaultSorting('id', 'desc');
        $this->setDataProvider($dataProvider);
    }

    /**
     * @param $key
     * @param $row
     * @return string
     */
    public function replaceFieldState($key, $row)
    {
        return StyleFormatter\State::vps($row[$key]);
    }

    /**
     * @param $key
     * @param $row
     * @return string
     */
    public function replaceFieldSize($key, $row)
    {
        return Decorators\Unit::decorate($row[$key], Decorators\Unit::GB);
    }

    public function replaceFieldBandwidthLimit($key, $row)
    {
        return Decorators\Unit::decorate($row[$key], Decorators\Unit::MB, Decorators\Unit::SECONDS);
    }


    /**
     * @param $key
     * @param $row
     * @return string
     */
    public function replaceFieldLowFreeSpaceThreshold($key, $row)
    {
        return Decorators\Unit::decorate($row[$key], Decorators\Unit::MB);
    }



}
