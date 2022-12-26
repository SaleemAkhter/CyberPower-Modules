<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Distribution;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;

/**
 * Class Model
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Model extends Serializer
{
    protected $locale;
    protected $availableLanguage;

    protected $id;
    protected $name;
    protected $distribution;
    protected $bitFormat;

    public function __construct($params)
    {
        $this->fill($params);
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getAvailableLanguage()
    {
        return $this->availableLanguage;
    }

    /**
     * @param mixed $availableLanguage
     */
    public function setAvailableLanguage($availableLanguage)
    {
        $this->availableLanguage = $availableLanguage;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDistribution()
    {
        return $this->distribution;
    }

    /**
     * @param mixed $distribution
     */
    public function setDistribution($distribution)
    {
        $this->distribution = $distribution;
    }

    /**
     * @return mixed
     */
    public function getBitFormat()
    {
        return $this->bitFormat;
    }

    /**
     * @param mixed $bitFormat
     */
    public function setBitFormat($bitFormat)
    {
        $this->bitFormat = $bitFormat;
    }


}
