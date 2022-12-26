<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs;

use ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Server as CoreServer;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\ServerSettings;
/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Server extends CoreServer
{
    public function settings()
    {
        return $this->hasMany(ServerSettings::class, "server_id");
    }

    public function scopeJoinOvhServerType($query)
    {
        return $query->join('OvhVpsAndDedicated_ServerSettings', function ($join) {
            $join->on("OvhVpsAndDedicated_ServerSettings.server_id", "=", "tblservers.id");
            $join->where('OvhVpsAndDedicated_ServerSettings.setting', '=', 'ovhServerType');
        });
    }
}
