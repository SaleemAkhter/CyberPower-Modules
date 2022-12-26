<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\IP;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Collections;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps\IP as IPItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\BaseVpsRepository;

/**
 * Class Repository
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Repository extends BaseVpsRepository
{
    public function get($ip)
    {
        return new IPItem($ip, $this->vpsId);
    }

    public function getAll()
    {
        $all = $this->vps->ips()->all();

        foreach ($all as &$item)
        {
            $item = $this->get($item->model()->getIpAddress());
        }
        return $all;
    }

    public function getAllToModel()
    {
        $all = $this->getAll();
        foreach ($all as &$item)
        {
            $item = $item->model();
        }

        return $all;
    }

    public function getAllToArray()
    {
        $all = $this->getAllToModel();
        foreach ($all as &$item)
        {
            $item = $item->toArray();
        }

        return $all;
    }
}