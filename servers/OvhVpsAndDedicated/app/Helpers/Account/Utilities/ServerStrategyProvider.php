<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities;

use function GuzzleHttpOvh\debug_resource;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Abstracts\Account;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps\Vps as VpsServer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server as DedicatedServer;

/**
 * Class StrategyProvider
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerStrategyProvider
{
    /**
     * @var Account
     */
    private $server;


    public function chooseServer(Client $client)
    {
        $nameSpace = 'ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Servers\\';
        $objectName = $nameSpace. ucfirst($client->getServerType());

        $this->server = new $objectName($client);
    }

    /**
     * @return Account
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param Account $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    public function getServerStrategy($params = null)
    {
        $client = new Client($params);
        if(!trim($client->getServiceName()))
        {
            return null;
        }

        switch ($client->getServerType())
        {
            case 'vps':
                return new VpsServer($client->getServiceName(), $params);
            case 'dedicated':
                return new DedicatedServer($client->getServiceName(), $params);
        }

        return null;
    }

}
