<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs;

class UsageUpdate
{

    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function update()
    {
        $api      = new Api($this->params);
        $result = $api->servers()->all();

        foreach ($result as $server)
        {
            $bwUsage = round($server->ingoingTraffic/1024/1024);
            $serverId = $this->params['serverid'];
            $domain = $server->name;

            Whmcs\Hosting::where('server', $serverId)->where('domain', $domain)->update(['bwusage' => $bwUsage]);
        }

    }
}
