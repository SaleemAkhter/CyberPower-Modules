<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


use ModulesGarden\Servers\VultrVps\App\Api\Repositories\BackupRepository;
use ModulesGarden\Servers\VultrVps\App\Api\Repositories\SnapshotRepository;

class Instance
{

    use AbstractObject;

    protected $id; //String
    protected $os; //String
    protected $ram; //int
    protected $disk; //int
    protected $mainIp; //String
    protected $vcpuCount; //int
    protected $region; //String
    protected $plan; //String
    protected $dateCreated; //Date
    protected $status; //String
    protected $allowedBandwidth; //int
    protected $netmaskV4; //String
    protected $gatewayV4; //String
    protected $powerStatus; //String
    protected $serverStatus; //String
    protected $v6Network; //String
    protected $v6MainIp; //String
    protected $v6NetworkSize; //int
    protected $label; //String
    protected $internalIp; //String
    protected $kvm; //String
    protected $tag; //String
    protected $osId; //int
    protected $appId; //int
    protected $imageId; //String
    protected $firewallGroupId; //String
    protected $features=[];
    protected $isoId;


    /**
     * @return mixed
     */
    public function getIsoId()
    {
        return $this->isoId;
    }

    /**
     * @param mixed $isoId
     * @return Instance
     */
    public function setIsoId($isoId)
    {
        $this->isoId = $isoId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Instance
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->path = sprintf("instances/%s", $this->id);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param mixed $os
     * @return Instance
     */
    public function setOs($os)
    {
        $this->os = $os;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * @param mixed $ram
     * @return Instance
     */
    public function setRam($ram)
    {
        $this->ram = $ram;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param mixed $disk
     * @return Instance
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMainIp()
    {
        return $this->mainIp;
    }

    /**
     * @param mixed $mainIp
     * @return Instance
     */
    public function setMainIp($mainIp)
    {
        $this->mainIp = $mainIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVcpuCount()
    {
        return $this->vcpuCount;
    }

    /**
     * @param mixed $vcpuCount
     * @return Instance
     */
    public function setVcpuCount($vcpuCount)
    {
        $this->vcpuCount = $vcpuCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     * @return Instance
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @param mixed $plan
     * @return Instance
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     * @return Instance
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Instance
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllowedBandwidth()
    {
        return $this->allowedBandwidth;
    }

    /**
     * @param mixed $allowedBandwidth
     * @return Instance
     */
    public function setAllowedBandwidth($allowedBandwidth)
    {
        $this->allowedBandwidth = $allowedBandwidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetmaskV4()
    {
        return $this->netmaskV4;
    }

    /**
     * @param mixed $netmaskV4
     * @return Instance
     */
    public function setNetmaskV4($netmaskV4)
    {
        $this->netmaskV4 = $netmaskV4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGatewayV4()
    {
        return $this->gatewayV4;
    }

    /**
     * @param mixed $gatewayV4
     * @return Instance
     */
    public function setGatewayV4($gatewayV4)
    {
        $this->gatewayV4 = $gatewayV4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPowerStatus()
    {
        return $this->powerStatus;
    }

    /**
     * @param mixed $powerStatus
     * @return Instance
     */
    public function setPowerStatus($powerStatus)
    {
        $this->powerStatus = $powerStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServerStatus()
    {
        return $this->serverStatus;
    }

    /**
     * @param mixed $serverStatus
     * @return Instance
     */
    public function setServerStatus($serverStatus)
    {
        $this->serverStatus = $serverStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getV6Network()
    {
        return $this->v6Network;
    }

    /**
     * @param mixed $v6Network
     * @return Instance
     */
    public function setV6Network($v6Network)
    {
        $this->v6Network = $v6Network;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getV6MainIp()
    {
        return $this->v6MainIp;
    }

    /**
     * @param mixed $v6MainIp
     * @return Instance
     */
    public function setV6MainIp($v6MainIp)
    {
        $this->v6MainIp = $v6MainIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getV6NetworkSize()
    {
        return $this->v6NetworkSize;
    }

    /**
     * @param mixed $v6NetworkSize
     * @return Instance
     */
    public function setV6NetworkSize($v6NetworkSize)
    {
        $this->v6NetworkSize = $v6NetworkSize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return Instance
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInternalIp()
    {
        return $this->internalIp;
    }

    /**
     * @param mixed $internalIp
     * @return Instance
     */
    public function setInternalIp($internalIp)
    {
        $this->internalIp = $internalIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKvm()
    {
        return $this->kvm;
    }

    /**
     * @param mixed $kvm
     * @return Instance
     */
    public function setKvm($kvm)
    {
        $this->kvm = $kvm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     * @return Instance
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOsId()
    {
        return $this->osId;
    }

    /**
     * @param mixed $osId
     * @return Instance
     */
    public function setOsId($osId)
    {
        $this->osId = $osId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param mixed $appId
     * @return Instance
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * @param mixed $imageId
     * @return Instance
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirewallGroupId()
    {
        return $this->firewallGroupId;
    }

    /**
     * @param mixed $firewallGroupId
     * @return Instance
     */
    public function setFirewallGroupId($firewallGroupId)
    {
        $this->firewallGroupId = $firewallGroupId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param mixed $features
     * @return Instance
     */
    public function setFeatures($features)
    {
        $this->features = $features;
        return $this;
    }

    public function isRunning()
    {
        if (is_null($this->powerStatus))
        {
            $this->details();
        }
        return $this->powerStatus == "running";
    }

    public function details()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $response = $this->apiClient->get($this->path);
        $this->apiClient->getJsonMapper()->map($response->instance, $this);
        return $response;
    }

    public function reboot()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->post($this->path . "/reboot");
    }

    public function start()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->post($this->path . "/start");
    }

    public function halt()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->post($this->path . "/halt");
    }

    public function delete()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->delete($this->path);
    }

    public function create(array  $setting)
    {
        $response   = $this->apiClient->post("instances", $setting);
        $this->setId($response->instance->id);
        return $response;
    }

    public function reinstall($hostname= null)
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $post = [];
        if($hostname){
            $post['hostname'] = $hostname;
        }
        return $this->apiClient->post($this->path . "/reinstall", $post);
    }

    public function snapshot( $id = null){
        return (new Snapshot())->setApiClient($this->apiClient)->setInstanceId($this->id)->setId($id);
    }

    /**
     * @return SnapshotRepository
     */
    public function snapshots( ){
        return (new SnapshotRepository($this->apiClient))
              ->findInstanceId($this->id);
    }

    public function backups( ){
        return (new BackupRepository($this->apiClient));
    }

    /**
     * @return BackupSchedule
     */
    public function backupSchedule( ){
        return (new BackupSchedule())->setApiClient($this->apiClient)->setInstanceId($this->id);
    }

    public function update(array  $setting)
    {
        return $this->apiClient->patch($this->path , $setting);
    }

    public function bandwidth( ){

        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->get($this->path."/bandwidth");
    }

    public function changeOs($os)
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $post = [
            'os_id' => $os
        ];
        return $this->apiClient->patch($this->path , $post);
    }

    public function hasAutomaticBackups(){
        if(empty($this->features)){
            $this->details();
        }
        return in_array('auto_backups',$this->features);
    }

    public function ipv4()
    {
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        return $this->apiClient->get($this->path.'/ipv4');
    }
}