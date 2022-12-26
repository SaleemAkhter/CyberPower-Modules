<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\IP;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class IP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IP extends Serializer
{
    /**
     * @var
     */
    protected $macAddress;
    /**
     * @var
     */
    protected $version;
    /**
     * @var
     */
    protected $gateway;
    /**
     * @var
     */
    protected $ipAddress;
    /**
     * @var
     */
    protected $type;
    /**
     * @var
     */
    protected $geolocation;
    /**
     * @var
     */
    protected $reverse;

    /**
     * IP constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->fill($params);
    }

    /**
     * @return mixed
     */
    public function getMacAddress()
    {
        return $this->macAddress;
    }

    /**
     * @param mixed $macAddress
     */
    public function setMacAddress($macAddress)
    {
        $this->macAddress = $macAddress;
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
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
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
    public function getGeolocation()
    {
        return $this->geolocation;
    }

    /**
     * @param mixed $geolocation
     */
    public function setGeolocation($geolocation)
    {
        $this->geolocation = $geolocation;
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


}