<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

/**
 * Select field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Select extends BaseField
{
    protected $id   = 'select';
    protected $name = 'select';
    protected $multiple = false;
    protected $formgroupclass="";
    protected $availableValues = [];

    protected $htmlAttributes = [
        '@change' => 'selectChangeAction($event)'
    ];
    public function getFormGroupClass()
    {
        return $this->formgroupclass;
    }
    public function setFormGroupClass($class)
    {
        if(is_array($class)){
            $class=implode(" ",$class);
        }
        $this->formgroupclass=$class;
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
