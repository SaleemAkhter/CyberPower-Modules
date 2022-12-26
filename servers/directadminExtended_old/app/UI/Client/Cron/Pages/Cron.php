<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Buttons\MassAction\Delete;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Cron extends DataTableApi implements ClientArea
{
    protected $id    = 'cronTable';
    protected $name  = 'cronTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn(new Column('minute'))
            ->addColumn(new Column('hour'))
            ->addColumn(new Column('day'))
            ->addColumn(new Column('month'))
            ->addColumn(new Column('week'))
            ->addColumn(new Column('command'));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
            ->addActionButton(new Buttons\Edit())
            ->addActionButton(new Buttons\Delete())
            ->addMassActionButton(new Delete());
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $data       = [
            'domain' => $this->getWhmcsParamByKey('domain')
        ];
        $results     = $this->userApi->cron->lists(new Models\Command\Cron($data))->toArray();

        foreach($results as $key => $result)
        {
            if($result['id'] == 'PATH')
            {
                unset($results[$key]);
                continue;
            }
            $results[$key]['id'] = base64_encode(json_encode($result));
        }

        $provider   = new ArrayDataProvider();

        $provider->setData($results);
        $provider->setDefaultSorting('name', 'ASC');

        $this->setDataProvider($provider);
    }
}
