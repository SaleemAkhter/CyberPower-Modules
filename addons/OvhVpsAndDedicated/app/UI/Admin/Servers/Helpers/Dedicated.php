<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers;

use ModulesGarden\Servers\Ovh\App\Helpers\Formatter\Formatter;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Vps as VpsManager;

/**
 * Class Dedicated
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Dedicated
{
    const MACHINE_TYPE = 'Dedicated';

    public static function assignValuesAndFormat($machines)
    {
        foreach ($machines as &$machine)
        {
            $machine['id'] = $machine['name'];
            $machine = Machines::formatData($machine);
        }

        return $machines;
    }

    public static function assignExtraValues($servers, $clientsGroupedByMachineName)
    {
        $servers = Machines::assignClientMachine($servers, $clientsGroupedByMachineName);
        $servers = Machines::assignValuesFromDb($servers, self::MACHINE_TYPE);

        return $servers;
    }


}