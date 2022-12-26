<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Buttons\MassAction\Delete;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class DomainsTable extends DataTableApi implements ClientArea
{
    protected $id    = 'domainsTable';
    protected $name  = 'domainsTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true)->setOrderable('ASC'));
        $this->addColumn((new Column('bandwidth'))->setOrderable());
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton($this->getLogsButton())
                ->addActionButton(new Buttons\Delete())
                ->addMassActionButton(new Delete());
    }

    public function getLogsButton()
    {
        $button = new ButtonRedirect('logsButton');

        $button
            ->setIcon('lu-zmdi lu-zmdi-collection-text')
            ->setRedirectParams(['subdomain' => ':id'])
            ->setRawUrl(BuildUrl::getUrl('Subdomains', 'logs'));

        return $button;
    }
    protected function loadData()
    {
        $this->loadUserApi();

        $subdomainData = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data       = [
                'domain' => $domain
            ];

            $subdomains = $this->userApi->subdomain->lists(new Models\Command\Subdomain($data));

             foreach($subdomains->subdomains as $key => $val)
             {
                 if(is_numeric($key))
                 {
                     $subdomainData[$val->subdomain_name]['name'] = $val->subdomain_name . '.' . $domain;
                     $subdomainData[$val->subdomain_name]['domain'] = $domain;
                     $subdomainData[$val->subdomain_name]['bandwidth'] = number_format(round($val->bandwidth / 1024 / 1024, 3 ), 3);
                     $subdomainData[$val->subdomain_name]['id'] =  base64_encode(json_encode($subdomainData[$val->subdomain_name]));
                 }
             }
        }
        $provider = new ArrayDataProvider();
        $provider->setData($subdomainData);
        $provider->setDefaultSorting('name', 'ASC');
        $this->setDataProvider($provider);
    }
}
