<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Create extends Serializer
{
    /*
     * @var string $name
     */

    protected $name;
    /*
     * @var array $inboundRules
     */
    protected $inboundRules;
    /*
     * @var array $outboundRules
     */
    protected $outboundRules;
    /*
     * @var array $dropletIds
     */
    protected $dropletIds;
    /*
     * @var array $tags
     */
    protected $tags;

    /**
     * @param mixed $name
     * @return Create
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $inboundRules
     * @return Create
     */
    public function setInboundRules($inboundRules)
    {
        $this->inboundRules = $inboundRules;
        return $this;
    }

    /**
     * @param mixed $outboundRules
     * @return Create
     */
    public function setOutboundRules($outboundRules)
    {
        $this->outboundRules = $outboundRules;
        return $this;
    }

    /**
     * @param mixed $dropletIds
     * @return Create
     */
    public function setDropletIds($dropletIds)
    {
        $this->dropletIds = $dropletIds;
        return $this;
    }

    /**
     * @param mixed $tags
     * @return Create
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function setDefaultInboundRule()
    {
        $this->inboundRules[] =[
            'protocol' => 'tcp',
            'ports' => 22,
            'sources' => [
                'addresses' =>['0.0.0.0/0', '::/0']
            ]
        ];
        return $this;
    }




}
