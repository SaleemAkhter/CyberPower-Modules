<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\IP as IPApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class IP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IPs extends AbstractApi
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

    public function one($ip)
    {
        return new IPApiItem($this->api, $this->client, $this->getPathExpanded($ip));
    }
}