<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\AccoutnActions;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class ChangePackage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ChangePackage extends AddonController
{
    public function execute($params = null)
    {
        $account = new AccoutnActions($params);
        return $account->changePackage();
    }
}
