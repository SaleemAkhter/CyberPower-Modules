<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Volume;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Serializer;

/**
 * Description of Criteria
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Create extends Serializer
{
    /*
     * var string $name
     * 
     */

    protected $name;

    /*
     * @var string $description;
     */
    protected $description;

    /*
     * @var integer $description;
     */
    protected $sizeInGigabytes;

    /*
     * @var string $description;
     */
    protected $regionSlug;

    public function fill()
    {
        //$this->name            = time() . $this->params->getDomain();
        $domain                = explode('.',$this->params->getDomain());
        $strLength = rand(9,12);
        $this->name            = $this->generateString($strLength) . $domain[0];

        $this->sizeInGigabytes = (int) $this->params->getVolume();
        $this->regionSlug      = $this->params->getRegion();
        $this->description     = $this->params->getDomain();
        return $this;
    }

    protected function generateString($length = 7)
    {
        $data = range('a', 'z');

        for($i = 0; $i<=$length; $i++)
        {
            $number = rand(0,count($data));
            $string .= $data[$number];
        }

        return $string;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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
