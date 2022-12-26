<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\RestorePoints as RestorePointsApiItem;

/**
 * Class RestorePoints
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RestorePoints extends AbstractApi
{
    public function all()
    {
        $response = $this->get();
        $collection = new Collection();
        foreach ($response as $item)
        {
            $collection->add($this->one($item));
        }
        return $collection->all();
    }

    public function one($id)
    {
        $response = $this->get($id);
        return new RestorePointsApiItem($this->api, $this->client, $this->getPathExpanded($id), $response);
    }
}