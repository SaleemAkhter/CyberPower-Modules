<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers\Validators;


use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Validators\BaseValidator;

class Ipv6Validator extends BaseValidator
{
    public function validate($data, $additionalData = null)
    {
        $parameters =$additionalData->request->all();
        $prefix = $parameters['formData']['prefix'];

        if (filter_var($prefix.$data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
        {
            return true;
        }

        $this->addValidationError('invalidIPv6');
        return false;
    }
}