<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Ovh\Connection as VpsConnection;
/**
 * Class StrategyConnection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class StrategyConnection
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getConnection()
    {
        return $this->chooseConnectionForServerType();
    }

    private function chooseConnectionForServerType()
    {
        switch ($this->client->getServerType())
        {
//            case Constants::DEDICATED:
//                return new DedicatedConnection($this->client);
            default:
                return new VpsConnection($this->client);
        }
    }

}