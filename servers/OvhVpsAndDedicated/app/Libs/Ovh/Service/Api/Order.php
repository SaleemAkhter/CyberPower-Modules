<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Cart;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Catalog;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Vps;

/**
 * Class Cart
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Order extends AbstractApi
{
    public function cart()
    {
        return new Cart($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function vps()
    {
        return new Vps($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function catalog()
    {
        return new Catalog($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

}