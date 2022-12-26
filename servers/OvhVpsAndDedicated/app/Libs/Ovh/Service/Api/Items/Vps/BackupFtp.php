<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class BackupFtp
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class BackupFtp extends AbstractApiItem
{
    public function authorizableBlocks()
    {
        return $this->get(__FUNCTION__);
    }

    public function password($params)
    {
        return $this->post(__FUNCTION__, $params);
    }
}