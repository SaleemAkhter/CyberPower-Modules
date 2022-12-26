<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps;

use GuzzleHttpOvh\Exception\ClientException;
use ModulesGarden\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps\Vps as VpsItem;

/**
 * Class Base
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Repository extends BaseRepository
{
    public function get($id = false)
    {
        $id = $id ? $id : $this->api->getClient()->getServiceName();

        return new VpsItem($id);
    }
    
    public function getAll()
    {
        $all = $this->api->vps->all();
        foreach ($all as $key => &$item)
        {
            try
            {
                $item = $this->get($item->model()->getName());
            }
            catch (\Exception $exception)
            {
                unset($all[$key]);
            }
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
