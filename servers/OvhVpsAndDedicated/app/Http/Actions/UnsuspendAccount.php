<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\AccoutnActions;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class UnuspendAccount
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class UnsuspendAccount extends AddonController
{
    public function execute($params = null)
    {
        $account = new AccoutnActions($params);
        return $account->unsuspendAccount();
    }

}