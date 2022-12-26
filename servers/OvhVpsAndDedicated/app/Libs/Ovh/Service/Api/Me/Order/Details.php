<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Me\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Me\Order\Detail;

/**
 * Class Details
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Details extends AbstractApi
{

    public function allIdsOnly()
    {
        return $this->get();
    }

    public function all()
    {
        $all = $this->get();
        $collection = new Collection();

        foreach ($all as $detailId)
        {
            $collection->add($this->one($detailId));
        }
        return $collection->all();
    }

    public function one($detailId)
    {
        return new Detail($this->api, $this->client, $this->getPathExpanded($detailId));
    }
}