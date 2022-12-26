<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Helpers\NetworkManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;


/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class NetworkInformation extends DataTable implements ClientArea, AdminArea
{

    protected $id          = 'networkInformationTable';
    protected $title       = 'networkInformationTable';
    protected $searchable  = false;
    protected $tableLength = "100";

    protected function loadHtml()
    {
        $this->addColumn((new Column('ipAddress')))
             ->addColumn((new Column('gateway')))
             ->addColumn((new Column('type')))
             ->addColumn((new Column('version')))
             ->addColumn((new Column('netmask')))
             ->addColumn((new Column('floatingIp')));
    }

    protected function loadData()
    {
        $dataProvider = new ArrayDataProvider();
        $dataHelper = new NetworkManager($this->whmcsParams);
        $dataProvider->setData($dataHelper->getNetworkInformation());
        $this->setDataProvider($dataProvider);
    }

    public function initContent()
    {
        
    }

    public function getTableLength()
    {
        return $this->tableLength;
    }

}
