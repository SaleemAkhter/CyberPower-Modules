<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Features;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\IpmiFeatures;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class IPme
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IPmi extends AbstractApiItem
{
    public function getAccess($type)
    {
        $params = [
            'type' => $type
        ];

        return $this->get('access', $params);
    }

    public function addAccess($type, $ttl, $ip = '')
    {
        $params = [
            'type' => $type,
            'ttl' => $ttl
        ];
        if(!empty($ip))
        {
            $params['ipToAllow'] = $ip; 
        }

        return $this->post('access', $params);
    }

    public function model()
    {
        return new IpmiFeatures($this->getInfo());
    }
}