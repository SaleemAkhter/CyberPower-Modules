<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;

class UnlimitedSwitcher extends Switcher
{
    protected $id   = 'unlimitedSwitcher';
    protected $name = 'nnlimitedSwitcher';
    protected $textFieldValue='';
    protected $textFieldName='';
    protected $width = 3;
    protected $labelClass=["col-xs-12","col-sm-3", "col-form-label"];
    protected $fieldClass=["col-xs-6","col-sm-3"];
    protected $switchClass=["col-xs-6","col-sm-3", "text-right"];
    public function isRawTitle()
    {
        return false;
    }

    public function setTextFieldName($name)
    {

        $this->textFieldName=$name;
    }
    public function getTextFieldName()
    {
        return str_replace("Unlimited", "", $this->getTitle());
    }
    public function setTextFieldValue($value)
    {
        $this->textFieldValue=$value;
        return $this;
    }
    public function getTextFieldValue()
    {
        return $this->textFieldValue;
    }
    public function getTextFieldTitle()
    {
        return $this->getTextFieldName();
    }
    public function getLabelClass()
    {
        return (is_array($this->labelClass))? implode(" ",$this->labelClass):$this->labelClass;
    }
    public function setLabelClass($class)
    {
        if(is_array($class)){
            $class=implode(" ",$class);
        }
        $this->labelClass=$class;
        return $this;
    }
    public function getFieldClass()
    {
        return (is_array($this->fieldClass))? implode(" ",$this->fieldClass):$this->fieldClass;
    }
    public function setFieldClass($class)
    {
        if(is_array($class)){
            $class=implode(" ",$class);
        }
        $this->fieldClass=$class;
        return $this;
    }
    public function getSwitchClass()
    {
        return (is_array($this->switchClass))? implode(" ",$this->switchClass):$this->switchClass;
    }
    public function setSwitchClass($class)
    {
        if(is_array($class)){
            $class=implode(" ",$class);
        }
        $this->switchClass=$class;
        return $this;
    }

}
