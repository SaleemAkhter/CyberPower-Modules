<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields;

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
    
    protected $avalibleValues = [];
    
    public function setValue($value)
    {
        if(isset($value['avalibleValues']))
        {
            $this->setAvalibleValues($value['avalibleValues']);
        }
        
        if(isset($value['value']))
        {
            $this->setSelectedValue($value['value']);
        }

        return $this;        
    }
    
    public function setSelectedValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    public function setAvalibleValues($values)
    {
        if(is_array($values))
        {
            $this->avalibleValues = $values;
        }
        
        return $this;
    }
    
    public function getAvalibleValues()
    {
        return $this->avalibleValues;
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
