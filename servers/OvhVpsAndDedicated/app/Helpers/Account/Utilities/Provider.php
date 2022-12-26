<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities;

use ModulesGarden\OvhVpsAndDedicated\App\Models\MachineReuseProducts;
use ModulesGarden\OvhVpsAndDedicated\App\Models\Machine;
/**
 * Class Provider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Provider
{
    public static function getReusableProducts($packageId)
    {
        $res =   MachineReuseProducts::select("*")
            ->join('OvhVpsAndDedicated_Machine', "OvhVpsAndDedicated_Machine.name", '=', "OvhVpsAndDedicated_ReuseProducts.name")
            ->where("OvhVpsAndDedicated_ReuseProducts.productId", '=', $packageId)
            ->where("OvhVpsAndDedicated_Machine.setting", '=', 'reuse')
            ->where("OvhVpsAndDedicated_Machine.value", '=', 'on')
            ->pluck('OvhVpsAndDedicated_Machine.name')
            ->toArray();

        return $res;
    }
}
