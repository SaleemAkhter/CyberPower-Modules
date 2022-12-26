<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Buttons\ChangeHostname;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Helpers\Server;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Helpers\ServerManager;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\isAdmin;
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
class ServerInformation extends DataTable implements ClientArea, AdminArea
{

    protected $id          = 'serverinformationTable';
    protected $title       = 'serverinformationTable';
    protected $searchable  = false;
    protected $tableLength = "100";

    protected function loadHtml()
    {
        $this->addColumn((new Column('name')))->addColumn((new Column('value')));
    }

    protected function loadData()
    {

        $dataProvider = new ArrayDataProvider();
        $data         = new ServerManager($this->whmcsParams);
        $dataProvider->setData($data->getInformation());
        $this->setDataProvider($dataProvider);
    }

    public function initContent()
    {
        $pageControler = new PageController($this->whmcsParams);
        if (isAdmin() === true || $pageControler->clientAreaChangeHostname()) {
            $this->addActionButton(new ChangeHostname());
        }
    }
    public function getTableLength()
    {
        return $this->tableLength;
    }

}
