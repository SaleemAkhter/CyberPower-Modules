<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;

class Databases extends Tabs implements ClientArea
{
    protected $id    = 'databasesPage';
    protected $name  = 'databasesPage';
    protected $title = 'databasesPage';

    public function initContent()
    {
        $tabs = [
            Helper\di(DatabasesTable::class),
            Helper\di(UsersTable::class)
        ];

        foreach ($tabs as $tab)
        {
            $this->addElement($tab);
        }
    }
}
