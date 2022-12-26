<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class SubModel
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SubModel extends Serializer
{
    protected $maximumAdditionnalIp;
    protected $datacenter;

    protected $disk;
    protected $offer;
    protected $version;
    protected $name;
    protected $availableOptions;

    protected $memory;
    protected $vcore;

    public function __construct($params)
    {
        $this->fill($params);
    }

    /**
     * @return mixed
     */
    public function getMaximumAdditionnalIp()
    {
        return $this->maximumAdditionnalIp;
    }

    /**
     * @param mixed $maximumAdditionnalIp
     */
    public function setMaximumAdditionnalIp($maximumAdditionnalIp)
    {
        $this->maximumAdditionnalIp = $maximumAdditionnalIp;
    }

    /**
     * @return mixed
     */
    public function getDatacenter()
    {
        return $this->datacenter;
    }

    /**
     * @param mixed $datacenter
     */
    public function setDatacenter($datacenter)
    {
        $this->datacenter = $datacenter;
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
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAvailableOptions()
    {
        return $this->availableOptions;
    }

    /**
     * @param mixed $availableOptions
     */
    public function setAvailableOptions($availableOptions)
    {
        $this->availableOptions = $availableOptions;
    }

    /**
     * @return mixed
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param mixed $memory
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;
    }

    /**
     * @return mixed
     */
    public function getVcore()
    {
        return $this->vcore;
    }

    /**
     * @param mixed $vcore
     */
    public function setVcore($vcore)
    {
        $this->vcore = $vcore;
    }


}