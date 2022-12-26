<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\Create;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons\Delete;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\RedirectButton;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataTable;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class FirewallList extends DataTable implements ClientArea, AdminArea {

    protected $id    = 'firewallListTable';
    protected $name  = 'firewallListTable';
    protected $title = 'firewallListTable';

    public function loadHtml() {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('inboundRules'))->setSearchable(true)->setOrderable(true))
            ->addColumn((new Column('outboundRules'))->setSearchable(true)->setOrderable(true));


    }

    public function initContent()
    {
        $this->addButton(new Create());
        $this->addActionButton($this->getRedirectButton());
        $this->addActionButton(new Delete());
    }

    protected  function getRedirectButton(){

        $button = new RedirectButton();

        $button->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-edit')
            ->setRawUrl($this->getURL())
            ->setRedirectParams(['firewallid' => ':id']);
        return $button;
    }
    protected function getURL(){
        $params = [
            'action' => 'productdetails',
            'id'     => $_GET['id'], //TODO - $this->>getReuest();
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'firewall',
            ];

        return 'clientarea.php?'. \http_build_query($params);
    }

    protected function loadData() {


        $firewallManager =  new FirewallManager($this->whmcsParams);
        $data = $firewallManager->getFirewallsList();

        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($data);
        $dataProvider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($dataProvider);
    }

}
