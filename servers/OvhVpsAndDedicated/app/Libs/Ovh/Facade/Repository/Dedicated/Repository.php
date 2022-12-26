<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;


/**
 * Class Repository
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Repository extends BaseRepository
{
    public function get($id = false)
    {
        $id = $id ? $id : $this->api->getClient()->getServiceName();
        return new Server($id);
    }

    public function getAll()
    {
        $all = $this->api->dedicated->server()->all();

        foreach ($all as &$item)
        {
            $item = $this->get($item->model()->getName());
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

    public function getAllToArrayWithExtraInformation()
    {
        $all = $this->getAllToModel();
        foreach ($all as &$item)
        {
            $service = $item->getName();
            $item = $item->toArray();
            $item += $this->api->dedicated->server()->one($service)->features()->backupFTP()->getInfo();

        }

        return $all;
    }


}