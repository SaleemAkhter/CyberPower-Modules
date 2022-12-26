<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers\Validators;

use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Validators\BaseValidator;

class DomainValidator extends BaseValidator
{
    public function validate($data, $additionalData = null)
    {
        if (strpos($data, '.') === false || $data[strlen($data) - 1] == '.' || $data[0] == '.')
        {
            $this->addValidationError('invalidDomain');
            return false;
        }
    }
}