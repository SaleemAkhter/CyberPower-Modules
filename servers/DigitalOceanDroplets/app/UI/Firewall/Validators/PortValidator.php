<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Validators;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Validators\BaseValidator;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-08-27
 * Time: 13:32
 */

class PortValidator extends BaseValidator
{
    protected $maxValue = 9999;
    protected $minValue = 0;

    public function __construct($maxValue = 9999, $minValue = 0)
    {
        if (is_int($maxValue) && $maxValue > 0)
        {
            $this->maxValue = (int) $maxValue;
        }

        if (is_int($minValue))
        {
            $this->minValue = (int) $minValue;
        }
    }

    protected function validate($data, $additionalData = null)
    {
        //Allow a empty port value
        if($data == '' || $data == 'all'){
            return true;
        }
        if(is_numeric($data)){
            return $this->checkNumber($data);
        }

        return $this->checkRange($data);
    }


    private function checkRange($data){
        $rangeData = explode('-', $data);


        if(count($rangeData) != 2){
            $this->addValidationError('invalidPortRange');
            return false;
        }
        if(!is_numeric($rangeData[0]) || !is_numeric($rangeData[1])){
            $this->addValidationError('PleaseProvideAPortValueBetween', false, ['minValue' => $this->minValue, 'maxValue' => $this->maxValue]);
            return false;
        }

        if($rangeData[0] < $this->minValue || $rangeData[1] > $this->maxValue){
            $this->addValidationError('PleaseProvideAPortValueBetween', false, ['minValue' => $this->minValue, 'maxValue' => $this->maxValue]);
            return false;
        }

        if($rangeData[0] > $rangeData[1]){
            $this->addValidationError('invalidPortRange');
            return false;
        }


        return true;
    }

    private function checkNumber($data){
        if($data < $this->minValue || $data > $this->maxValue){
            $this->addValidationError('PleaseProvideAPortValueBetween', false, ['minValue' => $this->minValue, 'maxValue' => $this->maxValue]);
            return false;
        }
        return true;
    }

}