<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;

class FloatingIp extends AbstractApi
{
    public function createAssigned($dropletID)
    {
        return $this->api->floatingIp()->createAssigned($dropletID);
    }

    public function getAllFloatingIp()
    {
        return $this->api->floatingIp()->getAll();
    }

    public function removeFloatingIp($ip)
    {
        return $this->api->floatingIp()->delete($ip);
    }

    public function isAssignedToDropletId($id)
    {
       $floatingIps = $this->getAllFloatingIp();

       foreach ($floatingIps as $floatingIp)
       {
           if($floatingIp->droplet && $floatingIp->droplet->id == $id) return true;
       }
       return false;
    }

    public function getByDropletId($id)
    {
        $floatingIps = $this->getAllFloatingIp();

        foreach ($floatingIps as $floatingIp)
        {
            if($floatingIp->droplet && $floatingIp->droplet->id == $id) return $floatingIp;
        }
        return false;
    }

    public function getOnlyUnassigned()
    {
        $floatingIps = $this->getAllFloatingIp();

        foreach ($floatingIps as $index => $floatingIp)
        {
            if($floatingIp->droplet) unset($floatingIps[$index]);
        }

        return $floatingIps;
    }

    public function assign($ip,$dropletId)
    {
        return $this->api->floatingIp()->assign($ip, $dropletId);
    }

    public function unassign($ip)
    {
        return $this->api->floatingIp()->unassign($ip);
    }
}