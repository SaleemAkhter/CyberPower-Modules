<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Me;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Me;

/**
 * Class Order
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Order extends AbstractApi
{
    public function all()
    {
        $order = $this->get();
        $collection = new Collection();
        foreach ($order as $o)
        {
            $collection->add($this->one($o));
        }
        return $collection->all();
    }

    public function one($id)
    {
        return new Me\Order($this->api, $this->client, $this->getPathExpanded($id));
    }
}