<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Server extends Serializer
{
    protected $datacenter;
    protected $professionalUse;
    protected $supportLevel;
    protected $ip;
    protected $commercialRange;
    protected $os;
    protected $state;
    protected $serverId;
    protected $bootId;
    protected $name;
    protected $rescueMail;
    protected $reverse;
    protected $monitoring;
    protected $rack;
    protected $rootDevice;
    protected $linkSpeed;

    public function __construct($params)
    {
        $this->fill($params);
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
    public function getProfessionalUse()
    {
        return $this->professionalUse;
    }

    /**
     * @param mixed $professionalUse
     */
    public function setProfessionalUse($professionalUse)
    {
        $this->professionalUse = $professionalUse;
    }

    /**
     * @return mixed
     */
    public function getSupportLevel()
    {
        return $this->supportLevel;
    }

    /**
     * @param mixed $supportLevel
     */
    public function setSupportLevel($supportLevel)
    {
        $this->supportLevel = $supportLevel;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getCommercialRange()
    {
        return $this->commercialRange;
    }

    /**
     * @param mixed $commercialRange
     */
    public function setCommercialRange($commercialRange)
    {
        $this->commercialRange = $commercialRange;
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
     */
    public function setOs($os)
    {
        $this->os = $os;
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

    /**
     * @return mixed
     */
    public function getServerId()
    {
        return $this->serverId;
    }

    /**
     * @param mixed $serverId
     */
    public function setServerId($serverId)
    {
        $this->serverId = $serverId;
    }

    /**
     * @return mixed
     */
    public function getBootId()
    {
        return $this->bootId;
    }

    /**
     * @param mixed $bootId
     */
    public function setBootId($bootId)
    {
        $this->bootId = $bootId;
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
    public function getRescueMail()
    {
        return $this->rescueMail;
    }

    /**
     * @param mixed $rescueMail
     */
    public function setRescueMail($rescueMail)
    {
        $this->rescueMail = $rescueMail;
    }

    /**
     * @return mixed
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * @param mixed $reverse
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;
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
    public function getRack()
    {
        return $this->rack;
    }

    /**
     * @param mixed $rack
     */
    public function setRack($rack)
    {
        $this->rack = $rack;
    }

    /**
     * @return mixed
     */
    public function getRootDevice()
    {
        return $this->rootDevice;
    }

    /**
     * @param mixed $rootDevice
     */
    public function setRootDevice($rootDevice)
    {
        $this->rootDevice = $rootDevice;
    }

    /**
     * @return mixed
     */
    public function getLinkSpeed()
    {
        return $this->linkSpeed;
    }

    /**
     * @param mixed $linkSpeed
     */
    public function setLinkSpeed($linkSpeed)
    {
        $this->linkSpeed = $linkSpeed;
    }



}