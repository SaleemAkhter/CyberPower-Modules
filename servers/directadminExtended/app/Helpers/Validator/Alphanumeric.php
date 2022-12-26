<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class Alphanumeric extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        if (ctype_alnum($data))
        {
            return true;
        }

        $this->addValidationError('invalidAlphanumeric');

        return false;
    }
}
