<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\Abstracts\ApiManagerBase;

/**
 * Class OvhAccount
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class OvhAccountOrder extends ApiManagerBase
{
    protected $orderId;

    public function __construct(Client $client)
    {
        parent::__construct($client);
    }


    public function getServiceNameFromOrder($orderId)
    {
        $this->orderId = $orderId;
        $vpsDetailId = min($this->getDetailsIds());
        $serviceDetails = $this->getServiceName($vpsDetailId);
        return preg_replace('/-linux$/', '', $serviceDetails['domain']);
    }

    private function getDetailsIds()
    {
        return $this->api->me->order()->one($this->orderId)->details()->allIdsOnly();
    }

    private function getServiceName($vpsDetailId)
    {
        return $this->api->me->order()->one($this->orderId)->details()->one($vpsDetailId)->getInfo();
    }
}