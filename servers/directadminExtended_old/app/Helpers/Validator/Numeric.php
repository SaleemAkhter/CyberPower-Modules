<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class Numeric extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        if (is_numeric($data))
        {
            return true;
        }

        $this->addValidationError('invalidNumeric');

        return false;
    }
}
