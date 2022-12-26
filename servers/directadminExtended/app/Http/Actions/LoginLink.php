<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

/**
 * Class SuspendAccount
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class LoginLink extends AddonController
{
    public function execute($params = null)
    {
        $sso = new Helpers\SSO($params['serviceid']);

        return $sso->getAdminTemplateToLoginLink();
    }
}