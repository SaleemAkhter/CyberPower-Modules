<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApi;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\BackupFtp as BackupFtpItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\BackupFtp\Access;

/**
 * Class BackupFtp
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class BackupFtp extends AbstractApi
{
    public function one()
    {
        $response = $this->get();
        return new BackupFtpItem($this->api, $this->client, $this->path, $response);
    }

    public function access()
    {
        return new Access($this->api, $this->client, $this->path);
    }
}