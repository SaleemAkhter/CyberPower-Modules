<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class EmailForwardersTable extends DataTableApi implements ClientArea
{
    protected $id    = 'emailForwardersTable';
    protected $name  = 'emailForwardersTable';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setSearchable(true)->setOrderable('ASC'))
                ->addColumn((new Column('email'))->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\Add())
                ->addActionButton(new Buttons\Edit())
                ->addActionButton(new Buttons\Delete())
        ->addMassActionButton(new Buttons\MassAction\Delete());
    }
    public function replaceFieldEmail($key, $row)
    {
         $recordsExplode = explode(',', $row[$key]);

         return implode('<br />', $recordsExplode);
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
            $response = $this->userApi->emailForwarder->lists(new Models\Command\EmailForwarder($data))->toArray();
            foreach ($response as $key => $each)
            {
                $response[$key]['domain'] = $domain;
                $response[$key]['id']     = $each['user'] . '@'. $domain;
            }
            $result = array_merge($result, $response);
        }
        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('id', 'ASC');
        
        $this->setDataProvider($provider);
    }
}
