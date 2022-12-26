<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\Servers\AwsEc2\Core\UI\Traits\HideButtonByColumnValue;
/**
 * Select field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Select extends BaseField
{
    use HideButtonByColumnValue;
    protected $id   = 'select';
    protected $name = 'select';
    protected $multiple = false;
    protected $helpText='';

    protected $availableValues = [];

    protected $htmlAttributes = [
        '@change' => 'selectChangeAction($event)'
    ];

    public function getHelpText()
    {
        return $this->helpText;
    }
     public function setHelpText($text)
    {
         $this->helpText=$text;
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
}
