<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Checkout\Checkout;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Cart\Vps as VpsCart;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Item;

/**
 * Class Cart
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Cart extends AbstractApiItem
{
    public function vps()
    {
        return new VpsCart($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function checkout()
    {
        return new Checkout($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function remove()
    {
        return $this->delete();
    }

    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function assign()
    {
        return $this->post(__FUNCTION__);
    }

    public function item()
    {
        return new Item($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}