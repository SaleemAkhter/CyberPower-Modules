<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class SecondaryDnsDomains
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SecondaryDnsDomains extends AbstractApiItem
{
    public function update($params)
    {
        return $this->put(false, $params);
    }

    public function remove()
    {
        return $this->delete();
    }

    public function dnsServer()
    {
        return $this->get(__FUNCTION__);
    }
}