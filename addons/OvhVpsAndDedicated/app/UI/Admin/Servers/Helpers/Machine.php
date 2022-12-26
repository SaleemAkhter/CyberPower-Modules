<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Service;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Client;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Product;
use ModulesGarden\Servers\Ovh\App\Helpers\Formatter\Formatter;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Machine
{
    const TYPE_VPS = 'VPS';

    public static function assignExtraValues($vpsMachines, $clientsGroupedByMachineName)
    {
        $vpsMachines = Machines::assignClientMachine($vpsMachines, $clientsGroupedByMachineName);
        $vpsMachines = Machines::assignValuesFromDb($vpsMachines, self::TYPE_VPS);

        return $vpsMachines;
    }

    public static function getProducts($serverid)
    {
        $query = Product::getToAssignQuery($serverid);
        $products = $query->pluck('name', 'id')->toArray();
        asort($products);

        return $products;
    }
}
