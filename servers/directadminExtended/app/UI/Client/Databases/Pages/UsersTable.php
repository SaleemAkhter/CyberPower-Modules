<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use Symfony\Component\Yaml\Tests\B;

class UsersTable extends RawDataTableApi implements ClientArea
{
    protected $id    = 'usersTable';
    protected $name  = 'usersTable';
    protected $title = 'usersTableTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('user'))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn((new Column('name'))->setSearchable(true)->setOrderable());
    }

    public function initContent()
    {
        $this->addButton(new Buttons\AddUser())
                ->addActionButton(new Buttons\ChangePassword())
                ->addActionButton(new Buttons\EditPrivileges())
                ->addActionButton(new Buttons\DeleteUser())
                ->addMassActionButton(new Buttons\MassAction\DeleteUser());
    }

    protected function loadData()
    {
        $this->loadUserApi();

        $result    = [];
        $databases = $this->userApi->database->lists()->response;;
        foreach ($databases as $database)
        {
            $users = $this->userApi->database->users(new Models\Command\Database(['name' => $database->getName()]))->toArray();
            foreach($users as $key => $user)
            {
                $users[$key]['id']   = base64_encode(serialize(['user' => $user['user'], 'database' => $database->getName()]));
                $users[$key]['name'] = $database->getName();
            }
            $result = array_merge($result, $users);
        }

        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('user', 'ASC');
        $this->setDataProvider($provider);
    }
}
