<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Projects;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Resources extends Serializer
{
    /**
     * @var int
     */
    protected $droplet;

    /**
     * @var string
     */
    protected $floatingIP;
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $firewall;

    /**
     * @var string
     */
    protected $volume;


    /**
     * @param mixed $droplet
     */
    public function setDroplet($droplet)
    {
        $this->droplet = $droplet;
    }

    /**
     * @param mixed $floatingIP
     */
    public function setFloatingIP($floatingIP)
    {
        $this->floatingIP = $floatingIP;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param mixed $firewall
     */
    public function setFirewall($firewall)
    {
        $this->firewall = $firewall;
    }

    /**
     * @param string $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }



    public function toArray()
    {
        $params = parent::toArray();
        $resources = [];

        foreach($params as $key => $value)
        {
            $resources[] = $this->createParam($key, $value);
        }

        return ['resources' => $resources];
    }

    private function createParam($param, $value)
    {
        return implode(':', [
            'do',
            strtolower($param),
            $value
        ]);
    }


}
