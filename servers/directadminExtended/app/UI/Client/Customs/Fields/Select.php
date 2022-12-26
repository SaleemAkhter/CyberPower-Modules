<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;
/**
 * Select field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Select extends BaseField
{
    protected $id   = 'select';
    protected $name = 'select';
    protected $formId='';
    protected $multiple = false;
    protected $containerClasses='col-lg-12';
    protected $availableValues = [];

    protected $htmlAttributes = [
        '@change' => 'selectChangeAction($event)'
    ];

    public function setSelectedValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function setAvailableValues($values)
    {
        if(is_array($values))
        {
            $this->availableValues = $values;
        }

        return $this;
    }

    public function getAvailableValues()
    {
        return $this->availableValues;
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

    public function enableMultiple()
    {
        $this->multiple = true;

        return $this;
    }

    public function disableMultiple()
    {
        $this->multiple = false;

        return $this;
    }
    public function setFormId($id)
    {
        $this->formId = $id;
        return $this;
    }
    public function getFormId()
    {
        return $this->formId;
    }
    public function setContainerClasss($classes)
    {
        $this->containerClasses = implode(" ",$classes);
        return $this;
    }
    public function getContainerClasss()
    {
        return $this->containerClasses;
    }
}
