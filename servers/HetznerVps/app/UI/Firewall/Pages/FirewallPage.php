<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Pages;

use DateTime;
use Exception;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\AddFirewallButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\DeleteFirewallButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\DeleteMassFirewallButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;
use function http_build_query;

class FirewallPage extends DataTable implements ClientArea, AdminArea
{

    protected $id = 'firewallTable';
    protected $name = 'firewallTable';
    protected $title = 'firewallTable';

    public function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable('ASC')->setSearchable(true))
            ->addColumn((new Column('created'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('amountIn'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('amountOut'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->initWhmcsParams();
        $api    = new Api($this->whmcsParams->getWhmcsParams());
        $server = $api->servers()->get($this->whmcsParams->getWhmcsParams()['customfields']['serverID']);
        if (!$server)
        {
            throw new Exception('Server does not exitst');
        }

        $this->addButton(new AddFirewallButton());
//        $this->addActionButton(new ApplyFirewallButton());
//        $this->addActionButton(new RemoveResourceButton());
        $this->addActionButton($this->getRedirectButton());
        $this->addActionButton(new DeleteFirewallButton());
        $this->addMassActionButton(new DeleteMassFirewallButton());
    }

    protected function getRedirectButton()
    {
        $button = new ButtonRedirect();

        $button->setIcon('lu-icon-in-button lu-zmdi lu-zmdi-edit')
            ->setRawUrl($this->getURL())
            ->setRedirectParams(['firewallid' => ':id']);
        return $button;
    }

    protected function getURL()
    {
        $params = [
            'action'  => 'productdetails',
            'id'      => $_GET['id'], //TODO - $this->>getReuest();
            'modop'   => 'custom',
            'a'       => 'management',
            'mg-page' => 'firewalls',
        ];
        return 'clientarea.php?' . http_build_query($params);
    }

    public function loadData()
    {
        $whmcsParams    = $this->getWhmcsParams();
        $dataManager    = new FirewallManager($whmcsParams);
        $data           = (array)$dataManager->getAll();
        $dataToProvider = [];
        foreach ($data as &$item)
        {
            $date            = new DateTime($item->created);
            $item->created   = date("Y-m-d H:i:s", $date->getTimestamp());
            $item->amountIn  = 0;
            $item->amountOut = 0;
            foreach ($item->appliedTo as $appiled)
            {
                if (($appiled->server->id) == $whmcsParams['customfields']['serverID'])
                {
                    foreach ($item->rules as $value)
                    {
                        if ($value->direction == 'in')
                        {
                            $item->amountIn += 1;
                        }
                        if ($value->direction == 'out')
                        {
                            $item->amountOut += 1;
                        }
                    }
                    $item->appiled    = 1;
                    $dataToProvider[] = [
                        'created'   => $item->created,
                        'amountIn'  => $item->amountIn,
                        'amountOut' => $item->amountOut,
                        'name'      => $item->name,
                        'id'        => $item->id
                    ];
                }
//                if ($item['appiled'] != 1) $item['noAppiled'] = 1;
            }
        }

        $provider = new ArrayDataProvider();
        $provider->setDefaultSorting('name', 'ASC');
        $provider->setData($dataToProvider);

        $this->setDataProvider($provider);
    }
}