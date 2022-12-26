<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Helpers\Logger;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Droplets\Create;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Models\Projects\Resources;
use ModulesGarden\Servers\HetznerVps\App\Models\CronTasks;
use ModulesGarden\Servers\HetznerVps\App\Service\CronTask\RegisterTask;
use ModulesGarden\Servers\HetznerVps\App\Traits\ParamsComponent;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use Symfony\Component\Yaml\Tests\A;

/**
 * Description of AccoutnActions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
trait AccountActions
{
    use ParamsComponent;

    private $api;

    private function checkServerIDIsEmpty()
    {
        if (!empty($this->params['customfields']['serverID'])) {
            throw new Exception(Lang::getInstance()->T('serverIsNotEmpty'));
        }
    }

    private function checkServerIDIsNotEmpty()
    {
        if (empty($this->params['customfields']['serverID'])) {
            throw new Exception(Lang::getInstance()->T('serverIsNotEmpty'));
        }
    }

    protected function removeVolumes($volumes = [])
    {
        $api = new Api($this->params);
        foreach ($volumes as $volumeID) {
            $volumeObject = $api->volumes()->get($volumeID);
            $volumeObject->detach();

            /**
             * Add cron task to remove volume
             */
            RegisterTask::deleteVolume($api->getClient()->getWhmcsServerID(), $volumeID);
        }
    }

    protected function removeKeyIfExist()
    {
        $keyID = $this->findSshKey();

        if ($keyID) {
            $api = new Api($this->params);
            $api->sshKeys()->get($keyID)->delete();
        }
    }

    protected function findSshKey()
    {
        $api = new Api($this->params);
        $keys = $api->sshKeys()->all();

        foreach ($keys as $key) {
            if ($key->name === $api->getClient()->getDomain()) {
                return $key->id;
            }
        }

        return false;
    }

    protected function getInstalledImage()
    {
        $api = new Api($this->params);
        return $api->servers()
            ->get($api->getClient()
                ->getServerID())->image;
    }

    protected function getType()
    {
        $api = new Api($this->params);

        return $api->serverTypes()->get($api->getClient()->getType());
    }

    protected function resizeVolumes($volumes = [])
    {
        $api = new Api($this->params);
        foreach ($volumes as $volumeID) {
            if ($api->getClient()->getVolume() <= $api->volumes()->get($volumeID)->size) {
                continue;
            }

            $api->volumes()->get($volumeID)->resize($api->getClient()->getVolume());
        }
    }

    protected function getImage()
    {
        $api = new Api($this->params);

        return $api->images()->get($api->getClient()->getImage());
    }


    protected function distributeFloatingIps()
    {
        $this->api = new Api($this->params);
        $serverID = $this->api->getClient()->getServerID();

        $allFloatingIps = $this->api->floatingIps()->all();
        $currentServerFloatingIpsIds = array();
        foreach ($allFloatingIps as $floatingIp) {
            if ($floatingIp->server == $serverID)
                $currentServerFloatingIpsIds[] = $floatingIp->id;
        }

        $floatingIpsCount = count($currentServerFloatingIpsIds);
        $difference = $this->api->getClient()->getIps() - $floatingIpsCount;

        if ($difference > 0)
            for ($i = 0; $i < $difference; $i++)
                RegisterTask::createFloatingIP($serverID, $this->params);
        else if ($difference < 0) {
            $toDelete = array_slice($currentServerFloatingIpsIds, 0, -$difference);
            RegisterTask::deleteFloatingIP($toDelete, $this->params);
        }
    }

    protected function backupsPermission($serverID)
    {
        $this->clearCronBackupsWithStatusOne();
        if ($this->api->getClient()->areBackupsEnabled())
            RegisterTask::enableBackups($serverID, $this->params);
        else if ($this->api->getClient()->areBackupsEnabled() !== null)
            RegisterTask::disableBackups($serverID, $this->params);
    }

    protected function createIP()
    {
        $serverID = $this->api->getClient()->getServerID();

        RegisterTask::createFloatingIP($serverID, $this->params);

        return 'success';
    }

    protected function clearCronDB()
    {
        $cronTasks = CronTasks::where('status', 0)->get();
        foreach ($cronTasks as $task) {
            if ($task->action == 'createFloatingIP' || $task->action == 'deleteFloatingIP' ||
                $task->action == 'enableBackups' || $task->action == 'disableBackups')
                $task->delete();
        }

    }

    protected function clearCronBackupsWithStatusOne()
    {
        $cronTasks = CronTasks::where('status', 1)->get();
        foreach ($cronTasks as $task) {
            if ($task->action == 'enableBackups' || $task->action == 'disableBackups')
                $task->delete();
        }
    }

}


