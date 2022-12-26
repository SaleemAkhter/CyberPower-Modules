<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disks extends AbstractApi
{
    public function all()
    {
        $respone = $this->get();

        $collection = new Collection();
        foreach ($respone as $id)
        {
            $collection->add($this->one($id));
        }

        return $collection->all();
    }

    public function one($id)
    {
        $response = $this->get($id);
        return new Disk($this->api, $this->client, $this->getPathExpanded($id), $response);
    }
}