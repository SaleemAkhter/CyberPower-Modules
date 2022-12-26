<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Validators\BaseValidator;

class NotEmptyValidator extends BaseValidator
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
        if(str_replace(' ', '', $data) == '')
        {
            $this->addValidationError('thisFieldCannotBeEmpty');
            return false;
        }

        return true;
    }
}