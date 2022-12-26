<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Item\Item as OrderItem;

/**
 * Class Item
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Item extends AbstractApi
{
    public function all()
    {
        $items      = $this->get();
        $collection = new Collection();

        foreach ($items as $id)
        {
            $collection->add($this->one($id));
        }
        return $collection->all();
    }

    public function one($id)
    {
        return new OrderItem($this->api, $this->client, $this->getPathExpanded($id));
    }
}