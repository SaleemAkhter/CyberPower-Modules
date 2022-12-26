<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Providers;

use ModulesGarden\DirectAdminExtended\App\Services\FTPEndPointService;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Server;

class FtpEndPointUpdate extends FtpEndPoint
{

    public function read()
    {
        $service    = new FTPEndPointService();
        $endpoint   = $service->findById($this->actionElementId);
        if (!$endpoint)
        {
            return;
        }
        foreach ($endpoint->toArray() as $prop => $val)
        {
            $this->data[$prop] = $val;
        }

        $serversSelect  = [];
        $servers        = Server::where('type', 'directadminExtended')->get();

        foreach ($servers as $server)
        {
            $serversSelect[$server->id] = $server->name;
        }
        $this->data['server_id']                = [];
        $this->availableValues['server_id']     = $serversSelect;
        $this->data['server_id']                = $endpoint->toArray()['server_id'];

        $this->data['id'] = $this->actionElementId;
    }
}
