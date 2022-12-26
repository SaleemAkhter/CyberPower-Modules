<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Features\BackupFTP;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Features\IPmi;

/**
 * Class Features
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Features extends AbstractApi
{
    public function backupFTP()
    {
        return new BackupFTP($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function ipmi()
    {
        return new IPmi($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}