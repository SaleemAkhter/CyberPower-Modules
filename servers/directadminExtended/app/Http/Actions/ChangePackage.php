<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
/**
 * Class ChangePackage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ChangePackage extends AddonController
{
    public function execute($params = null)
    {
        $settingsRespository = new Repository();
        $productSettings = $settingsRespository->getProductSettings($params['pid']);

        $changePackage = new Helpers\ChangePackage($params, $productSettings);
        $packageName = $changePackage->getPackageName();

        if($packageName !== 'custom')
        {
            return $changePackage->upgradeOnPackage($packageName);
        }
        else {
            return $changePackage->upgradeOnConfigOptions();
        }
    }
}
