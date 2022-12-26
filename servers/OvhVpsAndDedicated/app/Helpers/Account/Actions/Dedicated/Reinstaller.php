<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Blocker;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

/**
 * Class Reinstaller
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reinstaller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Api
     */
    private $api;

    private $reinstalled = false;

    private $serverId;


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api =Ovh::API($this->client);

    }

    public function run($serverId = false)
    {
        if(!$serverId)
        {
            $serverId = $this->getServerId();
        }

        if($this->reinstall($serverId))
        {
            $this->reinstalled = true;
            $this->serverId = $serverId;
        }

    }

    private function hasServerFromReusable()
    {
        $comparator = new Comparator();
        return $comparator->getReusable($this->client);
    }

    private function reinstall($serverId)
    {
        $config = $this->client->getDedicatedProductConfig();

        $dedicated = $this->api->dedicated->server()->one($serverId)->model();

//        if($dedicated->getOs() == $config->getSystemTemplate()) //always reinstall
//        {
//            return true;
//        }

        $params = [
            'templateName' => $config->getSystemTemplate(),
            'details' => [
                'language' => $config->getLanguage(),
            ]
        ];

        $this->api->dedicated->server()->one($serverId)->install()->start($params);



        return true;
    }


    public function getReinstalledServiceName()
    {
        return $this->serverId;
    }
    /**
     * @return mixed
     */
    public function isReinstalled()
    {
        return $this->reinstalled;
    }

    private function getServerId()
    {
        $serverId = $this->hasServerFromReusable();

        if(!$serverId)
        {
            throw new \Exception('Wait until admin assign server to your service');
        }

        if(Blocker::isServiceBlocked($serverId))
        {
            throw new \Exception('This server is blocked by module configuration: '. $serverId);
        }

        return $serverId;
    }
}
