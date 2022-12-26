<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\RebuildDropletFieldsHelper;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\ProductConfiguration;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Helpers\ImageManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebuildPage extends DataTable implements ClientArea, AdminArea
{

    protected $id    = 'rebuildTable';
    protected $name  = 'rebuildTable';
    protected $title = 'rebuildTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('id'))->setOrderable()->setSearchable(true))
                ->addColumn((new Column('distribution'))->setOrderable('ASC')->setSearchable(true))
                ->addColumn((new Column('name'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->addActionButton(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Buttons\RebuildConfirm());
    }

    protected function loadData()
    {
        
        $data = RebuildDropletFieldsHelper::getAvailableImages($this->whmcsParams);
        
        foreach($data as &$row){
            $row['distribution'] = Lang::getInstance()->absoluteT($row['distribution']);  
        }
      
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setDefaultSorting('distribution', 'asc');
        $dataProvider->setData($data);
        $this->setDataProvider($dataProvider);
    }

}
