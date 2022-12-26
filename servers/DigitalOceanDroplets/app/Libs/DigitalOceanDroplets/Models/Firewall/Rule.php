<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Firewall;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Rule
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Rule extends Serializer
{
    /*
     * @var string $type
     */

    protected $type;
    /*
     * @var string $protocol
     */
    protected $protocol;
    /*
     * @var string $port
     */
    protected $port;
    /*
     * @var array $addresses
     */
    protected $sources;

    /**
     * @param mixed $type
     * @return Rule
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $protocol
     * @return Rule
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @param mixed $port
     * @return Rule
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param mixed $sources
     * @return Rule
     */
    public function setSources($sources)
    {
        if(is_string($sources)){
            $this->sources = $sources;
        }
        $this->sources = $sources;
        return $this;
    }
    /*
     * @var array $tags
 */






}
