<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Cart;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Options
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Options extends AbstractApiItem
{
    public function add($params)
    {
        return $this->post(false, $params);
    }

    public function getOptions($params)
    {
        return $this->get(false, $params);
    }
}