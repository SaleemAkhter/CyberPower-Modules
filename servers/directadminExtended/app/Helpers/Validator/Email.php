<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class Email extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        if(!$data)
        {
            return true;
        }
        if (filter_var($data, FILTER_VALIDATE_EMAIL))
        {
            return true;
        }

        $this->addValidationError('invalidEmail');

        return false;
    }
}
