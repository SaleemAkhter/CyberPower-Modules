<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Disk;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disk extends Serializer
{

    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $monitoring;

    /**
     * @var
     */
    protected $lowFreeSpaceThreshold;

    /**
     * @var
     */
    protected $bandwidthLimit;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $size;

    /**
     * @var
     */
    protected $state;

    /**
     * Disk constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->fill($params);
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMonitoring()
    {
        return $this->monitoring;
    }

    /**
     * @param mixed $monitoring
     */
    public function setMonitoring($monitoring)
    {
        $this->monitoring = $monitoring;
    }

    /**
     * @return mixed
     */
    public function getLowFreeSpaceThreshold()
    {
        return $this->lowFreeSpaceThreshold;
    }

    /**
     * @param mixed $lowFreeSpaceThreshold
     */
    public function setLowFreeSpaceThreshold($lowFreeSpaceThreshold)
    {
        $this->lowFreeSpaceThreshold = $lowFreeSpaceThreshold;
    }

    /**
     * @return mixed
     */
    public function getBandwidthLimit()
    {
        return $this->bandwidthLimit;
    }

    /**
     * @param mixed $bandwidthLimit
     */
    public function setBandwidthLimit($bandwidthLimit)
    {
        $this->bandwidthLimit = $bandwidthLimit;
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
     */
    public function setType($type)
    {
        $this->type = $type;
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
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }


}