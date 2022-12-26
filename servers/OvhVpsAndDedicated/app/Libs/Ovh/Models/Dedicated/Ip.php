<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class Ip
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ip extends Serializer
{

    protected $version;
    protected $ipAddress;
    protected $mask;
    protected $fullIp;


    public function __construct($ip)
    {
        list($this->ipAddress, $this->mask, $this->fullIp) = explode('/', $ip);
        $this->fullIp .= "/{$this->mask}";
        $this->setVersion();

    }

    private function setVersion()
    {
        $this->version = filter_var($this->ipAddress,FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? 'v4' : 'v6';
    }
}