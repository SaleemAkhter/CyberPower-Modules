<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


class Snapshot
{

    use AbstractObject;

    protected $id;
    protected $description;
    protected $dateCreated; //Date
    protected $size; //int
    protected $status; //String
    protected $osId; //int
    protected $appId;
    protected $instanceId;

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     * @return Snapshot
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return Snapshot
     */
    public function setSize($size)
    {
        $this->size = $size;
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
     * @return Snapshot
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return Snapshot
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
     * @return Snapshot
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    } //int


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Snapshot
     */
    public function setId($id = null)
    {
        $this->id = $id;
        $this->path = "snapshots/".$id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Snapshot
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * @param mixed $instanceId
     * @return Snapshot
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    public function create(){
        $setting=[
            'instance_id' => $this->instanceId,
            'description' => $this->description,
        ];
        $response   = $this->apiClient->post("snapshots", $setting);
        $this->id   = $response->snapshot->id;
        return $response;

    }

    public function details(){
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $response = $this->apiClient->get($this->path);
        $this->apiClient->getJsonMapper()->map($response->snapshot, $this);
        return $response;
    }

    public function update(){
        $setting=[
            'description' => $this->description,
        ];
        return $this->apiClient->put($this->path, $setting);
    }

    public function restore(){
        if (is_null($this->instanceId))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        if (is_null($this->id))
        {
            throw new \InvalidArgumentException("Snapshot Id cannot be empty");
        }
        $setting=[
            'snapshot_id' => $this->id
        ];
        //https://api.vultr.com/v2/instances/{instance-id}/restore
        return $this->apiClient->post(sprintf("instances/%s/restore",$this->instanceId), $setting);
    }
}