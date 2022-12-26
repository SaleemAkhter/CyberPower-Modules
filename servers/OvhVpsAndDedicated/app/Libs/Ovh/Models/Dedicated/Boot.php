<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class Boot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Boot extends Serializer
{
    protected $bootType;
    protected $kernel;
    protected $bootId;
    protected $description;


    public function __construct($data)
    {
        $this->fill($data);
    }

    /**
     * @return mixed
     */
    public function getBootType()
    {
        return $this->bootType;
    }

    /**
     * @param mixed $bootType
     */
    public function setBootType($bootType)
    {
        $this->bootType = $bootType;
    }

    /**
     * @return mixed
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @param mixed $kernel
     */
    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return mixed
     */
    public function getBootId()
    {
        return $this->bootId;
    }

    /**
     * @param mixed $bootId
     */
    public function setBootId($bootId)
    {
        $this->bootId = $bootId;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }



}