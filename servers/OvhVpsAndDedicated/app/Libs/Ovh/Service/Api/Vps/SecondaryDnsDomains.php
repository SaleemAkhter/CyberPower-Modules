<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\SecondaryDnsDomains as SecondaryDnsDomainsApiItem;

/**
 * Class SecondaryDnsDomains
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SecondaryDnsDomains extends  AbstractApi
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

    public function one($domain)
    {
        $response = $this->get($domain);
        return new SecondaryDnsDomainsApiItem($this->api, $this->client, $this->getPathExpanded($domain), $response);
    }

    public function create($params)
    {
        return $this->post(false, $params);
    }
}