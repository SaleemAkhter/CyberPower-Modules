<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Ip extends AbstractModel implements ResponseLoad
{
    protected $address;
    protected $gateway;
    protected $linkedIps;
    protected $ns;
    protected $reseller;
    protected $status;
    protected $value;
    protected $freeIps;

    public function loadResponse($response, $function = null)
    {
        switch ($function) {
            case 'freeIp':
                $this->fillFromJson($response);
                return $this;
        }
        if(isset($response['list']))
        {
            foreach($response['list'] as $ipAdresss)
            {
                $self = new self(['address' => $ipAdresss]);
                $this->addResponseElement($self);
            }
        }
        else
        {
            $self = new self($response);
            $this->addResponseElement($self);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Ip
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
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
     * @return Ip
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinkedIps()
    {
        return $this->linkedIps;
    }

    /**
     * @param mixed $linkedIps
     * @return Ip
     */
    public function setLinkedIps($linkedIps)
    {
        $this->linkedIps = $linkedIps;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNs()
    {
        return $this->ns;
    }

    /**
     * @param mixed $ns
     * @return Ip
     */
    public function setNs($ns)
    {
        $this->ns = $ns;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReseller()
    {
        return $this->reseller;
    }

    /**
     * @param mixed $reseller
     * @return Ip
     */
    public function setReseller($reseller)
    {
        $this->reseller = $reseller;
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
     * @return Ip
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Ip
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setIps($ip)
    {
        $this->freeIps = $ip;
        return $this;
    }

    public function getIps()
    {
        return $this->freeIps;
    }


}
