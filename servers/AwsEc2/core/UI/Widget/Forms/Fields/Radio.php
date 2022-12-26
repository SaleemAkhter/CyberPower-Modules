<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields;

/**
 * Select field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Radio extends BaseField
{
    protected $id   = 'baseradio';
    protected $name = 'baseradio';

    protected $availableValues = [];

    protected $htmlAttributes = [
        // '@click' => 'selectRadioAction($event)'
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


}
