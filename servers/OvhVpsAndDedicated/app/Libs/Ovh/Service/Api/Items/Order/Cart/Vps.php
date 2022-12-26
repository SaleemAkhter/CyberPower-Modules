<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Cart;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends AbstractApiItem
{
    public function add($params)
    {
        return $this->post(false, $params);
    }

    public function options($params = [])
    {
        return new Options($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

}