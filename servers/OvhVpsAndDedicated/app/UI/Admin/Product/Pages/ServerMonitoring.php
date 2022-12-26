<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\CustomComponents\DetailsWidget;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Basics\InformationTable;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers\ServerMonitoringProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerMonitoring extends InformationTable implements ClientArea, AdminArea
{
    protected $id          = 'serverMonitoringTable';
    protected $title       = 'serverMonitoringTable';

    protected function loadData()
    {
        $data = (new ServerMonitoringProvider())->getMonitoringData();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
