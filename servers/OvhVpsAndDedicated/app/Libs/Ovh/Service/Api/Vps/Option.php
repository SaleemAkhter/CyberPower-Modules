<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Option as OptionApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class Option
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Option extends AbstractApi
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

    public function allToArray()
    {
        return $this->get();
    }


    public function one($option)
    {
        $response = $this->get($option);
        return new OptionApiItem($this->api, $this->client, $this->getPathExpanded($option), $response);
    }
}