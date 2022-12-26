<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseSection extends BaseContainer
{
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Fields;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Sections;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Buttons;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\Section;
    
    protected $id   = 'baseSection';
    protected $name = 'baseSection';
    
    public function loadDataToForm(&$dataProvider)
    {
        foreach($this->fields as &$field)
        {
            $field->setValue($dataProvider->getValueById($field->getId()));
            $avValues = $dataProvider->getAvailableValuesById($field->getId());
            if ($avValues && method_exists($field, 'setAvailableValues'))
            {
                $field->setAvailableValues($avValues);
            }
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
}
