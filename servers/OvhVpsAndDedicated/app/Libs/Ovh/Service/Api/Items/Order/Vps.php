<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\AutomatedBackup;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Cpanel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Ftpbackup;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Ip;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Plesk;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Windows;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Order\Vps\Upgrade;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends AbstractApiItem
{
    public function automatedBackup()
    {
        return new AutomatedBackup($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function cpanel()
    {
        return new Cpanel($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function ftpbackup()
    {
        return new Ftpbackup($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function ip()
    {
        return new Ip($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function plesk()
    {
        return new Plesk($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function snapshot()
    {
        return new Snapshot($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function windows()
    {
        return new Windows($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    public function upgrade()
    {
        return new Upgrade($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}