<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Features;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class BackupFTP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class BackupFTP extends AbstractApiItem
{
    public function createBackupSpace()
    {
        return $this->post();
    }

    public function access()
    {
        return $this->get(__FUNCTION__);
    }

    public function createAccess($params)
    {
        return $this->post(__FUNCTION__, $params);
    }
}