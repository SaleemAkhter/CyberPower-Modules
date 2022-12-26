<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Ip;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Ip
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ip extends AbstractApiItem
{
    public function reverse()
    {
        return new Reverse($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}