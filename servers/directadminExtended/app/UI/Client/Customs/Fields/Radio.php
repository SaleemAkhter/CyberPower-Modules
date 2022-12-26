<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;


class Radio extends BaseField
{
    protected $id   = 'baseradio';
    protected $name = 'baseradio';
    protected $availableValues = [];
    protected $labelposition='left';
    protected $width = 3;


    public function setLabelPosition($labelposition)
    {
        $this->labelposition = $labelposition;

        return $this;
    }
    public function getLabelPosition()
    {
       return  $this->labelposition;
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
}
