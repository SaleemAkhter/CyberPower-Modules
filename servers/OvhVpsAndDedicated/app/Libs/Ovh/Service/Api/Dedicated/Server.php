<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Blocker;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Server as DedicatedServer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Server extends AbstractApi
{
    public function all()
    {
        $items = $this->get();

        $collection = new Collection();

        $services = Blocker::getBlockedServices();

        foreach ($items as $serviceName)
        {
            try
            {
                if(in_array($serviceName, $services))
                {
                    throw new \Exception("Service: {$serviceName} is blocked for any actions. ");
                    continue;
                }

                $collection->add($this->one($serviceName));
            }
            catch (\Exception $e)
            {
                continue;
            }

        }
        return $collection->all();
    }

    public function allToModel()
    {
        $items = $this->all();
        foreach ($items as &$item)
        {
            $items = $item->model();
        }
        return $items;
    }

    public function allToArray()
    {
        $items = $this->all();
        foreach ($items as &$item)
        {
            $items = $item->getInfo();
        }
        return $items;
    }


    public function one($serviceName = false)
    {
        $serviceName = $serviceName ? $serviceName : $this->client->getServiceName();

        return new DedicatedServer($this->api, $this->client, $this->getPathExpanded($serviceName), $response);
    }

}