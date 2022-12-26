<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Me;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Me\Order\Details;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Order
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Order extends AbstractApiItem
{
    public function details()
    {
        return new Details($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}