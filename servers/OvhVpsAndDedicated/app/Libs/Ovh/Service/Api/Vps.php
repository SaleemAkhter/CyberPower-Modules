<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\BaseConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Country;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Path;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Vps as VpsApiItem;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @property $disks
 * @property $option
 */
class Vps extends AbstractApi
{
    public function all()
    {
        $vpses      = $this->get();

        $collection = new Collection();
        
        foreach ($vpses as $serviceName)
        {
            try
            {
                $collection->add($this->one($serviceName));
            }
            catch (\Exception $e)
            {
                continue;
            }

        }
        return $collection->all();
    }

    public function one($serviceName = false)
    {
        $serviceName = $serviceName ? $serviceName : $this->client->getServiceName();

        if (!$serviceName)
        {
            throw new \Exception("Service name is not specified");
        }

        return new VpsApiItem($this->api, $this->client, $this->getPathExpanded($serviceName));
    }

    public function datacenter($country = false)
    {
        $country = $country ? $country : $this->client->getCountry();
        if (!$country)
        {
            throw new \Exception("Country is not specified");
        }

        return $this->get(__FUNCTION__, [BaseConstants::COUNTRY => $country]);
    }
}