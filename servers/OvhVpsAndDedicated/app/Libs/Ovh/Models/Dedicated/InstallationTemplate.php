<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class InstallationTemplate extends Serializer
{
    use Lang;

    protected $availableLanguages;
    protected $category;
    protected $family;
    protected $customization;
    protected $distribution;
    protected $bitFormat;
    protected $templateName;
    protected $description;


    public function __construct($params)
    {
        $this->fill($params);
    }

    /**
     * @return mixed
     */
    public function getAvailableLanguages($lang = false)
    {
        if(!$lang)
        {
            return $this->availableLanguages;
        }
        $this->loadLang();
        $out = [];
        foreach ($this->availableLanguages as $language)
        {
            $out[$language] = $this->lang->absoluteTranslate($language);
        }
        return $out;
    }

    /**
     * @param mixed $availableLanguages
     * @return InstallationTemplate
     */
    public function setAvailableLanguages($availableLanguages)
    {
        $this->availableLanguages = $availableLanguages;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return InstallationTemplate
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param mixed $family
     * @return InstallationTemplate
     */
    public function setFamily($family)
    {
        $this->family = $family;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomization()
    {
        return $this->customization;
    }

    /**
     * @param mixed $customization
     * @return InstallationTemplate
     */
    public function setCustomization($customization)
    {
        $this->customization = $customization;
        return $this;
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
     * @return InstallationTemplate
     */
    public function setDistribution($distribution)
    {
        $this->distribution = $distribution;
        return $this;
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
     * @return InstallationTemplate
     */
    public function setBitFormat($bitFormat)
    {
        $this->bitFormat = $bitFormat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param mixed $templateName
     * @return InstallationTemplate
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
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
     * @return InstallationTemplate
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}
