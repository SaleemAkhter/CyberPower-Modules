<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Me\Order;

/**
 * Class Me
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Me extends AbstractApi
{
    public function details()
    {
        return $this->get();
    }

    public function order()
    {
        return new Order($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}