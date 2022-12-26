<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers\Validators;

use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Validators\BaseValidator;

class Ipv4Validator extends BaseValidator
{
    public function validate($data, $additionalData = null)
    {
        if (filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return true;
        }

        $this->addValidationError('invalidIPv4');
        return false;
    }
}