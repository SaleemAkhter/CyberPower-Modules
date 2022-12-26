<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class PricingField extends BaseField
{
    protected $id   = 'pricingField';
    protected $name = 'pricingField';
    
    protected $avalibleValues = '';


    public function setSelectedValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    public function setAvalibleValues($values)
    {
        $this->avalibleValues = $values;
        
        return $this;
    }
    
    public function getAvalibleValues()
    {
        return $this->avalibleValues;
    }    
}
