<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\AccoutnActions;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Logger;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class CreateAccount
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CreateAccount extends AddonController
{
    public function execute($params = null)
    {
        $account = new AccoutnActions($params);
        return $account->create();
    }
}
