<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers\Validators;

use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Validators\BaseValidator;

class IpValidator extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $ipv4 = (new Ipv4Validator())->validate($data, $additionalData);
        $ipv6 = (new Ipv6Validator())->validate($data, $additionalData);

        if ($ipv4) {
            return true;
        }
        if ($ipv6) {
            return true;
        }

        $this->addValidationError('InvalidIP');
        return false;
    }
}