<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers\Logger;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Droplets\Create;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume\Create as CreateVolume;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask\RegisterTask;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Traits\ParamsComponent;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Helpers\FirewallManager;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Helpers\NetworkManager;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume\Resize as ResizeVolume;

/**
 * Description of AccoutnActions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class AccoutnActions
{

    use ParamsComponent;

    /*
     * Set Params
     * 
     * @param arrya $paramns
     * @return void;
     */

    public function __construct($params)
    {
        $this->setParams($params);
    }

    private function checkServerIDIsEmpty()
    {
        if (!empty($this->params['customfields']['serverID']))
        {
            throw new Exception(Lang::getInstance()->T('serverIsNotEmpty'));
        }
    }

    private function checkServerIDIsNotEmpty()
    {
        if (empty($this->params['customfields']['serverID']))
        {
            throw new Exception(Lang::getInstance()->T('serverIsNotEmpty'));
        }
    }

    /*
     * Create new VM 
     */

    public function create()
    {
        $this->checkServerIDIsEmpty();
        try
        {
            $createModel = new Create($this->params);
            $createModel->fill();

            $createKey = $this->createSshKey();
            if (!empty($createKey))
            {
                $createModel->addSshKey($createKey);
            }

            $createVolume = $this->createVolume();
            if (!empty($createVolume))
            {
                $createModel->setVolumes($createVolume);
            }

            $api = new Api($this->params);

            $response = $api->droplet->createAccount($createModel);

            CustomFields::set($this->params['serviceid'], 'serverID', $response->id);

            $this->addAssignTask($response->id);

            Logger::addLogs($this->params['packageid'], 'CraeteDroplet', $createModel->toArray(), $response);
        }
        catch (Exception $ex)
        {
            if (!empty($createVolume))
            {
                $this->deleteVolume($createVolume);
            }

            if(!empty($createKey)){
                $this->deleteSshKey($createKey);
            }

            Logger::addLogs($this->params['packageid'], 'CraeteDroplet', $createModel->toArray(), $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    private function addAssignTask($dropletID)
    {
        $client = new Client($this->params);
        if ($client->getProject() !== 0)
        {
            \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask\RegisterTask::assignVM($client->getWhmcsServerID(), $client->getProject(), $dropletID);
        }

        if (isset($this->params['configoptions']['floatingips']))
        {
            $floatingIpsEnabled = (bool)$this->params['configoptions']['floatingips'];
        }
        else
        {
            $fieldDataProv      = new FieldsProvider($this->params['packageid']);
            $floatingIpsEnabled = $fieldDataProv->getField('floatingIpEnabled') == 'on';
        }

        if ($floatingIpsEnabled)
        {
            \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask\RegisterTask::assignFloatingIp($client->getWhmcsServerID(), $dropletID);
        }
    }

    protected function deleteVolume($id)
    {
        try
        {
            $api = new Api($this->params);
            $api->volume->deleteByID($id);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'DeleteVolume', 'VolumeID: ' . $id, $ex->getMessage());
        }
    }

    protected function deleteSshKey($id)
    {
        try
        {
            $api = new Api($this->params);
            $api->key->deleteKey($id);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'DeleteSshKey', 'KeyID: ' . $id, $ex->getMessage());
        }
    }

    protected function createVolume($forceRegionSlug = '')
    {

        $client = new Client($this->params);
        if ($client->getVolume() > 0)
        {
            try
            {
                $createVolume = new CreateVolume($this->params);
                $createVolume->fill();

                if(!empty($forceRegionSlug))
                {
                    $createVolume->setRegionSlug($forceRegionSlug);
                }

                $api     = new Api($this->params);
                $reponse = $api->volume->createVolume($createVolume);

                Logger::addLogs($this->params['packageid'], 'CraeteVolume', $createVolume->toArray(), $reponse);
                return $reponse->id;
            }
            catch (\Exception $ex)
            {
                Logger::addLogs($this->params['packageid'], 'CraeteVolume', $createVolume->toArray(), $ex->getMessage());
                throw $ex;
            }
        }
    }

    protected function createSshKey()
    {

        $client = new Client($this->params);

        if (!empty(trim($client->getSshKey())))
        {
            try
            {
                $sshKey = $this->findSSHKey($client);
                if (!empty($sshKey))
                {
                    return $sshKey;
                }
                $createKey = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Keys\Create($this->params);
                $createKey->fill();

                $api     = new Api($this->params);
                $reponse = $api->key->createKey($createKey);
                Logger::addLogs($this->params['packageid'], 'CraeteSSHKey', $createKey->toArray(), $reponse);
                return $reponse->id;
            }
            catch (\Exception $ex)
            {
                Logger::addLogs($this->params['packageid'], 'CraeteSSHKey', (!is_null($createKey)) ? $createKey->toArray() : '', $ex->getMessage());
                throw $ex;
            }
        }
    }

    protected function findSSHKey(Client $client)
    {
        $api = new Api($this->params);
        return $api->key->findID();
    }

    /*
     * Susspend WHMCS Account and power off VM
     */

    public function suspendAccount()
    {
        try
        {
            $this->checkServerIDIsNotEmpty();
            $vmHelper = new ServiceManager($this->params);
            $response = $vmHelper->powerOff();
            Logger::addLogs($this->params['packageid'], 'PowerOff', $this->params['serviceid'], $response);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'PowerOff', $this->params['serviceid'], $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    /*
     * Terminate WHMCS Account and power off VM
     */

    public function terminateAccount()
    {
        try
        {
            $client = new Client($this->params);
            $this->checkServerIDIsNotEmpty();
            $api     = new Api($this->params);
            $droplet = $api->droplet->one();

            $networkManager = new NetworkManager($this->params);
            $fip            = $networkManager->getFloatingIp($droplet->id);

            if ($fip)
            {
                $api->floatingIp->removeFloatingIp($fip);
            }

            (new SnapshotManager($this->params))->deleteSnapshots($droplet->snapshotIds);

            if (!empty($client->getFirewalls()))
            {
                $firewallManager = new FirewallManager($this->params);

                foreach($client->getFirewalls() as $id)
                {
                    $firewallManager->deleteFirewall($id);
                }
            }

            foreach ($droplet->volumeIds as $volume)
            {
                \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask\RegisterTask::deleteVolume($client->getWhmcsServerID(), $volume);
            }
            $droplet->delete();


            \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CustomFields\CustomFields::set($this->params['serviceid'], 'serverID', '');
            Logger::addLogs($this->params['packageid'], 'Delete', $this->params['serviceid'], 'success');
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'Delete', $this->params['serviceid'], $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    /*
     * Unsuspend WHMCS Account and power on VM
     */

    public function unsuspendAccount()
    {
        try
        {
            $this->checkServerIDIsNotEmpty();

            $vmHelper = new ServiceManager($this->params);
            $response = $vmHelper->powerOn();
            Logger::addLogs($this->params['packageid'], 'PowerOn', $this->params['serviceid'], $response);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'PowerOn', $this->params['serviceid'], $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    /*
     * Resize droplet
     */

    private function addPowerOnTask($droplet, $resizeActionID)
    {
        $client = new Client($this->params);
        RegisterTask::powerOn($client->getWhmcsServerID(), $droplet->id, $resizeActionID);

    }

    public function changePackage()
    {
        try
        {
            $this->checkServerIDIsNotEmpty();
            $api = new Api($this->params);
            $client = new Client($this->params);
            $fieldDataProv = new FieldsProvider($this->params['packageid']);
            $droplet = $api->droplet->one();

            /**
             * Handle floating ips
             */
            $this->handleFloatingIpActions($api);


            /**
             * Volume resize
             */
            if(count($droplet->volumeIds))
            {
                foreach ($droplet->volumeIds as $volume)
                {
                    $this->resizeVolume($volume, $droplet->region->slug);
                }
            }
            else
            {
                $createVolume = $this->createVolume($droplet->region->slug);
                if (!empty($createVolume))
                {
                    $this->attachVolume($createVolume, $droplet->id, $droplet->region->slug);
                }
            }
            /**
             * Change tags
             */
            $resources['resources'] = [];

            $resource                = new \stdClass();
            $resource->resource_id   = (string)$droplet->id;
            $resource->resource_type = $droplet->getItemType();

            array_push($resources['resources'], $resource);

            foreach ($droplet->volumeIds as $v)
            {
                $volume                = new \stdClass();
                $volume->resource_id   = $v;
                $volume->resource_type = 'volume';

                array_push($resources['resources'], $volume);
            }

            foreach ($droplet->tags as $tag)
            {
                $api->droplet->untagAResource($tag, $resources);
            }

            foreach($client->getTags() as $tag)
            {
                $api->droplet->tagAResource($tag, $resources);
            }

            /**
             * Resize droplet
             */
            $response = $api->droplet->resize();
            $this->addPowerOnTask($droplet, $response->id);

            $vmHelper = new ServiceManager($this->params);

            /**
             * Manage backups
             */
            if (isset($this->params['configoptions']['backups']))
            {
                $backupEnabled = (bool)$this->params['configoptions']['backups'];
            }
            else
            {

                $backupEnabled = $fieldDataProv->getField('backup') == 'on';
            }

            if ($backupEnabled)
            {
                $vmHelper->enableBackups();
            }
            else
            {
                $vmHelper->disableBackups();
            }





            Logger::addLogs($this->params['packageid'], 'Resize', $this->params['serviceid'], $response);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'Resize', $this->params['serviceid'], $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    /*
     * Reset VM password 
     */

    public function resetPassword()
    {
        try
        {
            $this->checkServerIDIsNotEmpty();
            $vmHelper = new ServiceManager($this->params);
            $response = $vmHelper->passwordReset();
            Logger::addLogs($this->params['packageid'], 'PasswordReset', $this->params['serviceid'], $response);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'PasswordReset', $this->params['serviceid'], $ex->getMessage());
            return $ex->getMessage();
        }
        return 'success';
    }

    private function handleFloatingIpActions($api)
    {
        if ($this->isFloatingIpEnabled())
        {
            $this->assignFloatingIp($api);
        }
        else
        {
            $this->unassignFloatingIp($api);
        }
    }

    private function isFloatingIpEnabled()
    {
        if (isset($this->params['configoptions']['floatingips']))
        {
            $floatingIpsEnabled = (bool)$this->params['configoptions']['floatingips'];
        }
        else
        {
            $fieldDataProv      = new FieldsProvider($this->params['packageid']);
            $floatingIpsEnabled = $fieldDataProv->getField('floatingIpEnabled') === 'on';
        }
        return $floatingIpsEnabled;
    }

    private function assignFloatingIp($api)
    {
        $dropletId            = $api->droplet->one()->id;
        $wasfloatingIpEnabled = $api->floatingIp->isAssignedToDropletId($dropletId);
        if (!$wasfloatingIpEnabled)
        {
            $floatingIp = $api->floatingIp->getOnlyUnassigned();
            if ($floatingIp)
            {
                $floatingIp = $floatingIp[0]->ip;
                $createNew  = false;
            }
            else
            {
                $floatingIp = null;
                $createNew  = true;
            }
            RegisterTask::assignFloatingIpToDropletId($this->params['serverid'], $dropletId, $floatingIp, $createNew);
        }
    }

    private function unassignFloatingIp($api)
    {
        $dropletId           = $api->droplet->one()->id;
        $assignedFloatingIps = $api->floatingIp->getByDropletId($dropletId);
        if ($assignedFloatingIps)
        {
            RegisterTask::unassignFloatingIpToDropletId($this->params['serverid'], $assignedFloatingIps->ip);
        }
    }

    protected function resizeVolume($id, $forceRegionSlug = '')
    {
        try
        {
            $resizeVolume = new ResizeVolume($this->params);
            $resizeVolume->fill();

            if(!empty($forceRegionSlug))
            {
                $resizeVolume->setRegionSlug($forceRegionSlug);
            }

            $api = new Api($this->params);
            $api->volume->resizeVolume($id, $resizeVolume);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'ResizeVolume', 'VolumeID: ' . $id, $ex->getMessage());
        }
    }

    protected function attachVolume($id, $dropletId, $regionSlug)
    {
        try
        {
            $api = new Api($this->params);
            $api->volume->attach($id, $dropletId, $regionSlug);
        }
        catch (Exception $ex)
        {
            Logger::addLogs($this->params['packageid'], 'AttachVolume', 'VolumeID: ' . $id, $ex->getMessage());
        }
    }
}
