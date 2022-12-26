<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields;

/**
 * Base Switcher Field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Switcher extends BaseField
{
    protected $id   = 'baseSwitcher';
    protected $name = 'baseSwitcher';
    protected $fieldlabel='';
    protected $fieldlabeldescription='';
    protected $fieldlabelsmalltext='';
    protected $width = 3;


    public function getSwitchFieldLabel()
    {
        return $this->fieldlabel;
    }
    public function setSwitchFieldLabel($text)
    {
         $this->fieldlabel=$text;
         return $this;
    }
    public function getSwitchLabelDescription()
    {
        return $this->fieldlabeldescription;
    }
    public function setSwitchLabelDescription($text)
    {
         $this->fieldlabeldescription=$text;
         return $this;
    }
    public function getSwitchFieldLabelSmalltext()
    {
        return $this->fieldlabelsmalltext;
    }
    public function setSwitchFieldLabelSmalltext($text)
    {
         $this->fieldlabelsmalltext=$text;
         return $this;
    }
}
