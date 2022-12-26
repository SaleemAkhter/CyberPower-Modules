<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class UsernameLength extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        if (strlen($data)>=0 && strlen($data)<=10)
            {
                return true;
            }
        $this->addValidationError('invalidUsernameLength');

        return false;
    }
}
