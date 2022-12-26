<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\IpExplicator;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\Ip as IpModel;
use Ovh\Api;

/**
 * Class Ips
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ips extends AbstractApi
{
    public function __construct(Api $api, Client $client, $path = '')
    {
        parent::__construct($api, $client, $path);
    }

    public function all()
    {
        return $this->get();
    }

    public function allExtendedIncludingMaskAndParenIp()
    {
        $ips = $this->all();

        $out = [];
        foreach ($ips as $ip)
        {
            $result = IpExplicator::cidrl($ip);

            if(!is_array($result))
            {
                array_push($out, $ip);
                continue;
            }
            $out = array_merge($out, $result);
        }
        return $out;
    }

    public function allToModel()
    {
        $ips = $this->allExtendedIncludingMaskAndParenIp();

        $collection = new Collection();
        foreach ($ips as $ip)
        {
            $collection->add(new IpModel($ip));
        }
        return $collection->all();
    }

    public function toArray()
    {
        $ips = $this->allToModel();

        foreach ($ips as &$ip)
        {
            $ip = $ip->toArray();
        }
        return $ips;
    }
}