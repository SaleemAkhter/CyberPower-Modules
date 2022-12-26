<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Ip\Ip as IpItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 */
class Ip extends AbstractApi
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

    public function one($ip)
    {
        $ip = urlencode($ip);
        return new IpItem($this->api, $this->client, $this->getPathExpanded($ip));
    }

}