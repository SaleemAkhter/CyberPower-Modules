<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Abstracts;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Interfaces\AccountAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting;

/**
 * Class Account
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class Account implements AccountAction
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function assignDomainAndIp($domain, $ip)
    {
        Hosting::where('id', $this->client->getHostingID())->update(['dedicatedip' => $ip]);
    }
}