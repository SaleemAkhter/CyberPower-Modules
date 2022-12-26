<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\AddRuleButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\DeleteMassRuleButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\DeleteRuleButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons\EditRuleButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\DataTable;

class FirewallRules extends DataTable implements ClientArea, AdminArea
{
    use RequestObjectHandler;

    protected $id = 'firewallRulesTable';

    protected $firewall;
    protected $title = null;

    public function loadHtml()
    {
        $this->addColumn((new Column('protocol'))->setOrderable('ASC')->setSearchable(true))
            ->addColumn((new Column('port'))->setOrderable()->setSearchable(true))
            ->addColumn((new Column('ip'))->setSearchable(true))
            ->addColumn((new Column('direction'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new AddRuleButton());
        $this->addActionButton(new EditRuleButton());
        $this->addActionButton(new DeleteRuleButton());
        $this->addMassActionButton(new DeleteMassRuleButton());
    }

    public function loadData()
    {
        $this->loadRequestObj();
        $whmcsParams     = $this->getWhmcsParams();
        $firewallManager = new FirewallManager($whmcsParams);
        $data            = $firewallManager->getFirewallRules($this->request->get('firewallid', 0));
        $dataToProvider  = [];

        foreach ($data as $key => $rule)
        {
            $dataToProvider[] = [
                'ip'        => implode(',', $rule->ip),
                'fid'       => $rule->firewallId,
                'name'      => $rule->firewallName,
                'direction' => $rule->direction,
                'protocol'  => $rule->protocol,
                'port'      => $rule->port,
                'ruleId'    => $key,
                'id'        => $key
            ];
        }

        $provider = new ArrayDataProvider();
        $provider->setDefaultSorting('protocol', 'ASC');
        $provider->setData($dataToProvider);

        $this->setDataProvider($provider);
    }

    public function replaceFieldDirection($key, $row)
    {
        return strtoupper($row['direction']);
    }

    public function replaceFieldProtocol($key, $row)
    {
        return strtoupper($row['protocol']);
    }
}