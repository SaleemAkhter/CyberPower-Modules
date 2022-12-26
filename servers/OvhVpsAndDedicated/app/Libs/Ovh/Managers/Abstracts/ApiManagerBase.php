<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\Abstracts;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;

/**
 * Class ApiManagerBase
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ApiManagerBase
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api = Ovh::API($client);
    }

}