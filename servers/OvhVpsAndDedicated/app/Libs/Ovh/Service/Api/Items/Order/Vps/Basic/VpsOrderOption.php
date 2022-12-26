<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Basic;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class BasicVpsOrderOption
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class VpsOrderOption extends AbstractApiItem
{
    public function getPossiblyDurations()
    {
        return $this->getInfo();
    }

    public function addOption($duration)
    {
        return $this->post($duration);
    }

    public function getOptionDetails($duration)
    {
        return $this->get($duration);
    }
}