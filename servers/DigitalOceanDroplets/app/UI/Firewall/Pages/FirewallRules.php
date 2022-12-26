<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\Create;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\CreateRule;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\DeleteRule;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\EditRule;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\MassDeleteRule;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\AddFirewallRule;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\RawDataTable\RawDataTable;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FirewallRules extends DataTable implements ClientArea, AdminArea {

    use RequestObjectHandler;

    protected $id    = 'firewallRulesTable';

    protected $firewall;
    protected $title = null;

    public function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('protocol'))->setSearchable(true)->setOrderable(true))
                ->addColumn((new Column('ports'))->setSearchable(true)->setOrderable(true))
                ->addColumn((new Column('sources'))->setSearchable(true)->setOrderable(true));
    }

    public function initContent()
    {
        $this->addMassActionButton(new MassDeleteRule());
        $this->addActionButton(new EditRule());
        $this->addActionButton(new DeleteRule());
        $this->addButton(new CreateRule());

    }

    protected function loadData($col = [])
    {
        $this->loadRequestObj();
        $firewallManager =  new FirewallManager($this->whmcsParams);
        $data = $firewallManager->getFirewallRules($this->requestObj->get('firewallid', 0));
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $dataProvider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($dataProvider);
    }

}
