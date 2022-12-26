<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

/**
 * Class TerminateAccount
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class TerminateAccount extends AddonController
{
    public function execute($params = null)
    {
        Helpers\DirectAdminWHMCS::load();

        return directadmin_terminateaccount($params);
    }

}