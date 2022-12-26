<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class PrivilegesTable extends RawDataTableApi implements ClientArea
{
    protected $id    = 'privilegesTable';
    protected $name  = 'privilegesTable';
    protected $title = 'privilegesTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('database'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('user'))->setSearchable(true)->setOrderable());
    }

    public function initContent()
    {
        $this->addButton(new Buttons\AddPrivileges())
                ->addActionButton(new Buttons\EditPrivileges());
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result     = [];
        $databases  = $this->userApi->database->lists()->response;
        $data       = [];

        foreach ($databases as $database)
        {
            $users = $this->userApi->database->users(new Models\Command\Database(['name' => $database->getName()]))->response;
            foreach($users as $user)
            {
                $data[] = [
                    'database'  => $database->getName(),
                    'user'      => $user->user,
                    'id'        => $user->user
                ];
            }
        }

        $provider = new ArrayDataProvider();

        $provider->setData($data);
        $provider->setDefaultSorting('database', 'ASC');
        $this->setDataProvider($provider);
    }
}
