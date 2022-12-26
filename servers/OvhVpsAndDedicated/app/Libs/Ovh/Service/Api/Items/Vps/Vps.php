<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api\Contacts;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Option;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Tasks;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Veeam;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics\Collection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Disks;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Distribution;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\BackupFtp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\IPs;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps\Templates;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Vps as VpsModel;

/**
 * Description of Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends AbstractApiItem
{

    /**
     * @return mixed
     */
    public function start()
    {
        return $this->post(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function stop()
    {
        return $this->post(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function update($params = [])
    {
        return $this->put(false, $params);
    }

    public function rescue()
    {
        $params = [
            'netbootMode' => 'rescue',
        ];

        return $this->put(false, $params);
    }

    public function unrescue()
    {
        $params = [
            'netbootMode' => 'local',
        ];

        return $this->put(false, $params);
    }

    /**
     * @return mixed
     */
    public function terminate()
    {
        return $this->post(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function serviceInfos()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function reboot()
    {
        return $this->post(__FUNCTION__);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function reinstall($params = [])
    {
        return $this->post(__FUNCTION__, $params);
    }

    /**
     * @return mixed
     */
    public function setPassword()
    {
        return $this->post(__FUNCTION__);
    }


    /**
     * @param $type
     * @return mixed
     */
    public function usage($type)
    {
        return $this->get('use', $type);
    }

    /**
     * @return VpsModel
     */
    public function model()
    {
        return new VpsModel($this->getInfo());
    }

    /**
     * @return mixed
     */
    public function availableUpgrade()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function changeContact($params)
    {
        return $this->post(__FUNCTION__, $params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function confirmTermination($params)
    {
        return $this->post(__FUNCTION__, $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function createSnapshot($params = [])
    {
        return $this->post(__FUNCTION__, $params);
    }

    /**
     * @return mixed
     */
    public function datacenter()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function getConsoleUrl()
    {
        return $this->post(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function ipCountryAvailable()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function monitoring()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function openConsoleAccess($params)
    {
        return $this->get(__FUNCTION__, $params);
    }

    /**
     * @return mixed
     */
    public function secondaryDnsNameServerAvailable()
    {
        return $this->get(__FUNCTION__);
    }

    /**
     * @return BackupFtp
     */
    public function backupftp()
    {
        return new BackupFtp($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Disks
     */
    public function disks()
    {
        return new Disks($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Distribution
     */
    public function distribution()
    {
        return new Distribution($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return IPs
     */
    public function ips()
    {
        return new IPs($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Option
     */
    public function option()
    {
        return new Option($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return SecondaryDnsDomains
     */
    public function secondaryDnsDomains()
    {
        return new SecondaryDnsDomains($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Tasks
     */
    public function tasks()
    {
        return new Tasks($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return Templates
     */
    public function templates()
    {
        return new Templates($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Veeam
     */
    public function veeam()
    {
        return new Veeam($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }

    /**
     * @return AutomatedBackup
     */
    public function automatedBackup()
    {
        return new AutomatedBackup($this->api, $this->client, $this->getPathExpanded(__FUNCTION__));
    }
}
