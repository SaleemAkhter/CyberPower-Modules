<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Forms\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;

class SelectWithText extends Select
{
    protected $id   = 'selectWithText';
    protected $name = 'selectWithText';
    protected $formId='';
    protected $multiple = false;
    protected $containerClasses='col-lg-12';
    protected $availableValues = [];
    protected $inputLabel='';
    protected $inputName='';
    protected $inputValue='';
    protected $inputPlaceholder='';
    protected $isDisabled=false;
    protected $htmlAttributes = [
        '@change' => 'selectChangeAction($event)'
    ];

    public function getInputPlaceholder()
    {
        return $this->inputPlaceholder;
    }
    public function setInputPlaceholder($inputPlaceholder)
    {
        $this->inputPlaceholder=$inputPlaceholder;
        return $this;
    }
    public function getInputName()
    {
        return $this->inputName;
    }
    public function setInputName($inputname)
    {
        $this->inputName=$inputname;
        return $this;
    }
    public function getInputValue()
    {
        return $this->inputValue;
    }
    public function setInputValue($inputValue)
    {
        $this->inputValue=$inputValue;
        return $this;
    }
    public function getInputLabel()
    {
        return $this->inputLabel;
    }
    public function setInputLabel($inputLabel)
    {
        $this->inputLabel=$inputLabel;
        return $this;
    }
    public function getIsInputDisabled()
    {
        return $this->isDisabled;
    }
    public function setIsInputDisabled($disabled)
    {
        $this->isDisabled=$disabled;
        return $this;
    }
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
