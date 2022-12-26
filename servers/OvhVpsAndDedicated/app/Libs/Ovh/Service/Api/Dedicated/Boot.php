<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Boot as BootItem;

/**
 * Class Install
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Boot extends AbstractApi
{
    public function allIds($type)
    {
        $params = [
            'bootType' => $type
        ];
        return $this->get(false, $params);
    }

    public function all($type)
    {
        $collection = new Collection();
        foreach ($this->allIds($type) as $id)
        {
            $collection->add($this->one($id));
        }
        return $collection->all();
    }

    public function allToModel($type)
    {
        $collection = new Collection();
        foreach ($this->allIds($type) as $id)
        {
            $collection->add($this->one($id)->model());
        }
        return $collection->all();
    }

    public function allToArray($type)
    {
        $collection = new Collection();
        foreach ($this->allIds($type) as $id)
        {
            $collection->add($this->one($id)->getInfo());
        }
        return $collection->all();
    }

    public function one($bootId)
    {
        return new BootItem($this->api, $this->client, $this->getPathExpanded($bootId));
    }

    public function make($bootType)
    {

    }
}