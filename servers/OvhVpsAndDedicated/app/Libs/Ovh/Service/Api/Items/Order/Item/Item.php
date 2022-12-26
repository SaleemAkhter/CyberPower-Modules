<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Item;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Item\Configuration;

/**
 * Class Item
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Item extends AbstractApiItem
{
    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function remove()
    {
        return $this->delete();
    }

    public function configuration()
    {
        return new Configuration($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function requiredConfiguration()
    {
        return $this->get(__FUNCTION__);
    }

}