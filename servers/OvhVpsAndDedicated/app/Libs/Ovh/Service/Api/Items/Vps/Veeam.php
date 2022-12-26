<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\RestorePoints;

/**
 * Class Veeam
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Veeam extends AbstractApiItem
{
    public function restoredBackup()
    {
        return new RestoredBackup(false, $this->api, $this->client, $this->getPathExpanded('restoredBackup'));
    }

    public function restorePoints()
    {
        return new RestorePoints($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

}