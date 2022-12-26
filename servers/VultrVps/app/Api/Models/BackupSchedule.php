<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


class BackupSchedule
{
    use AbstractObject;
    protected $instanceId;
    protected $enabled;
    protected $type;
    protected $nextScheduledTimeUtc;
    protected $hour;
    protected $dow;
    protected $dom;

    /**
     * @return mixed
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * @param mixed $instanceId
     * @return BackupSchedule
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        if (is_null($this->instanceId))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $this->path = sprintf("instances/%s/backup-schedule",$this->instanceId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     * @return BackupSchedule
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return BackupSchedule
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNextScheduledTimeUtc()
    {
        return $this->nextScheduledTimeUtc;
    }

    /**
     * @param mixed $nextScheduledTimeUtc
     * @return BackupSchedule
     */
    public function setNextScheduledTimeUtc($nextScheduledTimeUtc)
    {
        $this->nextScheduledTimeUtc = $nextScheduledTimeUtc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     * @return BackupSchedule
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDow()
    {
        return $this->dow;
    }

    /**
     * @param mixed $dow
     * @return BackupSchedule
     */
    public function setDow($dow)
    {
        $this->dow = $dow;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param mixed $dom
     * @return BackupSchedule
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    public function details(){
        if (is_null($this->instanceId))
        {
            throw new \InvalidArgumentException("Instance Id cannot be empty");
        }
        $response = $this->apiClient->get($this->path);
        $this->apiClient->getJsonMapper()->map($response->backup_schedule, $this);
        return $response;
    }

    public function update(){
        $setting=[
            'type' => $this->type,
            'hour' => $this->hour,
            'dow' => $this->dow,
            'dom' => $this->dom,
        ];
        return $this->apiClient->post($this->path, $setting);
    }
}