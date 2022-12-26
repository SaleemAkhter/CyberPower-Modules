<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Keys;

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
    protected $public_key;

    public function fill()
    {
        $this->name       = "WHMCS-". $this->params->getHostingID();
        $this->public_key = $this->params->getSshKey();
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPublicKey($publicKey)
    {
        $this->public_key = $publicKey;
    }

}
