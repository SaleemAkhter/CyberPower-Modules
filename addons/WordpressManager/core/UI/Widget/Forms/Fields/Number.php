<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

/**
 * Number Field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Number extends BaseField
{
    protected $id   = 'number';
    protected $name = 'number';
    protected $maxValue = null;
    protected $minValue = null;

    public function __construct($minValue = null, $maxValue = null)
    {
        parent::__construct();

        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        
        $this->isIntNumberBetween($this->minValue, $this->maxValue);
    }

    public function getMinValue()
    {
        return $this->minValue;
    }

    public function getMaxValue()
    {
        return $this->maxValue;
    }
}
