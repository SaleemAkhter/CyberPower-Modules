<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Autoresponders extends DataTableApi implements ClientArea
{
    protected $id    = 'autorespondersTable';
    protected $name  = 'autorespondersTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('autoresponder'))->setSearchable(true, Column::TYPE_STRING)->setOrderable('ASC'))
                ->addColumn(new Column('cc'));
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
            $data       = [
                'domain' => $domain
            ];
            $response = $this->userApi->autoresponder->lists(new Models\Command\Autoresponder($data))->toArray();

            foreach ($response as $autoresponder)
            {
                $autoresponder['domain'] = $domain;
                $autoresponder['autoresponder'] = $autoresponder['user'] . '@' . $domain;
                $autoresponder['id'] = $autoresponder['user'] . '@' . $domain;
                $result[] = $autoresponder;
            }
        }

        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('autoresponder', 'ASC');

        $this->setDataProvider($provider);
    }
}
