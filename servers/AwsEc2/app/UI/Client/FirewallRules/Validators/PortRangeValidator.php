<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators;


use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Validators\BaseValidator;

class PortRangeValidator extends BaseValidator
{

    /**
     * return true if data is valid, false if not,
     * add error messages to $errorsList
     *
     * @param $data           mixed
     * @param $additionalData mixed
     * @return boolean
     */
    protected function validate( $data, $additionalData = null )
    {
        $str = str_replace(' ', '', $data);
        if(strtolower($data) === 'all')
            return true;

        if(!preg_match('/^[\d-]+$/', $str))
        {
            $this->addValidationError('onlyPortCharacters');
            return false;
        }

        return true;
    }
}