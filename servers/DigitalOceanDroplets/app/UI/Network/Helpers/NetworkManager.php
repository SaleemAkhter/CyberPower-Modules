<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Droplet;

/**
 * Description of ServerDetails
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class NetworkManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getNetworkInformation()
    {
        $vm = $this->getVM();

        $floatingIp = $this->getFloatingIp($vm->id)?:'Unavailable';

        return $this->prepareNetworkDateToTable($vm,$floatingIp);
    }

    /*
     * Get VM object
     *
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Droplet $vm
     */

    public function getVM()
    {
        $api = new Api($this->params);
        return $api->droplet->one();
    }

    public function getFloatingIp( $dropletID )
    {
        $api = new Api($this->params);

        $floatingIps = $api->floatingIp->getAllFloatingIp();

        foreach ( $floatingIps as $floatingIp )
        {
            if ( isset($floatingIp->droplet, $floatingIp->droplet->id) && $floatingIp->droplet->id == $dropletID)
            {
                return $floatingIp->ip;
            }
        }
        return false;
    }
    /*
     * Get params required to get VM
     * 
     * @return array $params
     */

    private function prepareNetworkDateToTable(Droplet $vm,$floatingIp = null)
    {
        $networks = [];
        foreach ($vm->networks as $network)
        {
            $networks[] = [
                'ipAddress' => ($network->version == 6) ? $this->ipv6Compress($network->ipAddress) : $network->ipAddress,
                'gateway'   => ($network->version == 6) ? $this->ipv6Compress($network->gateway) : $network->gateway,
                'type'      => ucfirst($network->type),
                'version'   => $this->renameIP($network->version),
                'netmask'   => $this->setMask($network),
                'floatingIp'=> $floatingIp
            ];
        }
        return $networks;
    }

    private function renameIP($version)
    {
        switch ($version)
        {
            case '6':
                return "IPv6";
            case '4':
                return "IPv4";
            default:
                return "Undefined";
        }
    }

    private function setMask($network)
    {
        if (!empty($network->netmask) && !empty($network->cidr))
        {
            return $network->netmask . ', /' . $network->cidr;
        }
        if (!empty($network->netmask))
        {
            return $network->netmask;
        }
        if (!empty($network->cidr))
        {
            return '/' . $network->cidr;
        }
    }

    private function ipv6Compress($ip)
    {
        // Shorten first group of zeros
        if (substr($ip, 0, 4) === '0000')
            $ip = substr_replace($ip, ':0', 0, 4);
        // Shorten full groups of zeros
        $ip = str_replace(':0000', ':0', $ip);
        // Remove leading zeros
        $ip = preg_replace('/:0{1,3}(?=\w)/', ':', $ip); //return $ip;
        // Remove longest extra group of zeros per [RFC 5952](http://tools.ietf.org/html/rfc5952)
        $z  = ':0:0:0:0:0:0:0:'; // Set chain
        while (strpos($ip, '::') === false && strlen($z) >= 5)
        { // While no :: and chain still possible
            $pos = strpos($ip, $z);
            if ($pos !== false)
            {
                $ip = substr_replace($ip, '::', $pos, strlen($z));
                break;
            } // Replace chain and break
            $z = substr($z, 0, strlen($z) - 2); // cut away one '0:' to shorten the chain
        }
        if (substr($ip, 1, 1) !== ':')
            return ltrim($ip, ':'); // Remove initial : if not a ::
        return $ip; // return
    }

}
