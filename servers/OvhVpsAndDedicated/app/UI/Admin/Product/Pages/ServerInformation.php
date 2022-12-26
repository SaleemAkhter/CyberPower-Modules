<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Basics\InformationTable;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers\ServerInformationProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;


/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerInformation extends InformationTable implements ClientArea, AdminArea
{
    protected $id = 'serverInformation';
    protected $name = 'serverInformation';
    protected $title = 'serverInformation';

    protected function loadData()
    {
        $data = (new ServerInformationProvider())->getVpsInformation();
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }
}

