<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class RestoredBackup
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RestoredBackup extends AbstractApiItem
{
    public function remove()
    {
        return $this->delete();
    }
}