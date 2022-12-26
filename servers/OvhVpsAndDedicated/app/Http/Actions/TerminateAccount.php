<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\AccoutnActions;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class TerminateAccount
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class TerminateAccount extends AddonController
{
    public function execute($params = null)
    {
        if (!$params['customfields']['serverName']){
            return 'success';
        }
        $accountAction = new AccoutnActions($params);
        return $accountAction->terminateAccount();
    }

}