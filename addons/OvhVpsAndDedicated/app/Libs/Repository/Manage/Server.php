<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage;

use ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs\Server as ServerModel;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Server
{
    const OVH = 'OvhVpsAndDedicated';

    public static function getOvhServers()
    {
        $query = ServerModel::select('id', 'name', 'OvhVpsAndDedicated_ServerSettings.value as ovhServerType')
            ->where('type', '=', self::OVH)
            ->joinOvhServerType()
            ->get();

        return $query;
    }
}
