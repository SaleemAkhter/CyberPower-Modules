<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Item;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class Configuration
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Configuration extends AbstractApiItem
{
    public function remove()
    {
        return $this->delete();
    }
}