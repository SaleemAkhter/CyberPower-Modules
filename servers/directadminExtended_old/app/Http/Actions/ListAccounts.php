<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;



class ListAccounts extends AddonController
{
    public function execute($params = null)
    {
        Helpers\DirectAdminWHMCS::load();

        return directadmin_listaccounts($params);
    }
}