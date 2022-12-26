<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class RestorePoints
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RestorePoints extends AbstractApiItem
{
    public function remove()
    {
        return $this->delete();
    }
}