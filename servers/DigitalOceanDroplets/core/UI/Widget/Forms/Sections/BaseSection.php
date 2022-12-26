<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseSection extends BaseContainer
{
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Fields;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Sections;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Buttons;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Section;
    
    protected $id   = 'baseSection';
    protected $name = 'baseSection';
    
    public function loadDataToForm(&$dataProvider)
    {
        foreach($this->fields as &$field)
        {
            $field->setValue($dataProvider->getValueById($field->getId()));
        }
        
        foreach ($this->sections as &$section)
        {
            $section->loadDataToForm($dataProvider);
        }
    }
    
    public function loadDataToFormByName(&$dataProvider)
    {
        foreach ($this->fields as &$field)
        {
            $field->setValue($dataProvider->getValueByName($field->getName()));
            if ($dataProvider->isDisabledById($field->getId()))
            {
                $field->disableField();
            }
        }

        foreach ($this->sections as &$section)
        {
            $section->loadDataToFormByName($dataProvider);
        }
    }    
    
    /**
     * Adds field object to field list
     * @return $this
     */
    public function addField($field)
    {
        if($this->groupFieldsBySectionName === true)
        {
            $field->setName($this->name.'['.$field->getName().']');
            $field->setId($this->name.'['.$field->getId().']');
        }
        
        $this->fields[$field->getId()] = $field;

        return $this;
    }    
}
