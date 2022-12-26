<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers;

use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Machine as MachineManager;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;

/**
 * Class Machines
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Machines
{
    public static function assignValuesFromDb($servers, $machineType)
    {
        $dbMachines = MachineManager::getAllGroupedByVpsName();

        foreach ($servers as &$server)
        {
            foreach ($dbMachines as $machineName => $settings)
            {
                if($server['name'] == $machineName && $settings['server'] == $machineType)
                {
                    $server['id'] = $server['name'];
                    $server['reuse'] = $settings['reuse'];
                }
            }
            $server = self::formatData($server);
        }

        return $servers;
    }

    public static function assignClientMachine($servers, $clientsGroupedByMachineName)
    {
        foreach ($servers as &$server)
        {
            $server['client'] = isset($clientsGroupedByMachineName[$server['name']]) ? $clientsGroupedByMachineName[$server['name']]['id'] : 0;
        }

        return $servers;
    }

    public static function formatData($vpses)
    {
        $toFormat = [
            'state'     => Formatter::UCFIRST
        ];

        return Formatter::format($vpses, $toFormat);
    }
}