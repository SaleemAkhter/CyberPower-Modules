<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order\Formatted\Formatted;

/**
 * Class Catalog
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Catalog extends AbstractApi
{
    public function formatted()
    {
        return new Formatted($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}