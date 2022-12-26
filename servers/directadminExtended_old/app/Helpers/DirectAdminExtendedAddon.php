<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Models\AddonModule;

class DirectAdminExtendedAddon
{
    public static function isActive($throw = false)
    {
        $model = new AddonModule();
        $isEnabled = $model->where('module', '=' , 'DirectAdminExtended')->first();

        if($isEnabled)
        {
            return true;
        }

        if($throw)
        {
            throw new \Exception('The addon of DirectAdmin Extended For WHMCS has to be active in order to proceed.');
        }

        return false;
    }
}
