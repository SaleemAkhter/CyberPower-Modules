<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Criteria
 *
 * @author Damian Lipski <damian@modulesgarden.com>
 */
class Resize extends Serializer
{
    /*
     * @var integer $description;
     */
    protected $sizeInGigabytes;

    /*
     * @var string $regionSlug;
     */
    protected $regionSlug;

    public function fill()
    {
        $this->sizeInGigabytes = (int) $this->params->getVolume();
        $this->regionSlug      = $this->params->getRegion();
        return $this;
    }
    
    public function setSizeInGigabytes($sizeInGigabytes)
    {
        $this->sizeInGigabytes = (int) $sizeInGigabytes;
        return $this;
    }

    public function setRegionSlug($regionSlug)
    {
        $this->regionSlug = $regionSlug;
        return $this;
    }

}
