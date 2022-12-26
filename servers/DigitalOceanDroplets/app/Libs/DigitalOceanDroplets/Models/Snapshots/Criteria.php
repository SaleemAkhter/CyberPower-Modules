<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Snapshots;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Criteria
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Criteria extends Serializer
{
    /*
     * var string $type
     * 
     * available types = ['droplet', 'volume']
     */

    protected $type;

    /*
     * @var boolean $private;
     */
    protected $private;

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setPrivate($private)
    {
        $this->private = $private;
    }

}
