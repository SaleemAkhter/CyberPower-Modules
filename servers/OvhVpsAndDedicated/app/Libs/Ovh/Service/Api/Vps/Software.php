<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Software as SoftwareApiItem;

/**
 * Class Software
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Software extends AbstractApi
{
    public function all()
    {
        $response = $this->get();
        $collection = new Collection();
        foreach ($response as $item)
        {
            $collection->add($this->one($item));
        }
    }

    public function one($id)
    {
        $response = $this->get($id);
        return new SoftwareApiItem($this->api, $this->client, $this->getPathExpanded($id), $response);
    }
}