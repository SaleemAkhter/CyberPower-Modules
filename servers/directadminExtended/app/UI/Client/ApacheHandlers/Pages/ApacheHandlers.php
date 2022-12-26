<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ApacheHandlers extends DataTableApi implements ClientArea
{
    protected $id    = 'ApacheHandlersTable';
    protected $name  = 'ApacheHandlersTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('domain'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('name'))->setSearchable(true)->setOrderable())
            ->addColumn((new Column('extension'))->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\Edit())
            ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result = [];
        foreach ($this->getDomainList() as $domain)
        {
            $data     = [
                'domain' => $domain
            ];
            $response = $this->userApi->apacheHandler->lists(new Models\Command\ApacheHandler($data))->toArray();

            foreach ($response as $key => $each)
            {
                $each['domain'] = $domain;
                $each['id'] = base64_encode(json_encode($each));
                $response[$key] = $each;
            }
            $result = array_merge($result, $response);
        }

        $provider = new ArrayDataProvider();
        $provider->setData($result);
        $provider->setDefaultSorting('domain', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
