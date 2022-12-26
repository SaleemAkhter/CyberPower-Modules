<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class SpamScore extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        if (ctype_digit($data) && (int)$data >= 1 && (int)$data <= 50)
        {
            return true;
        }

        $this->addValidationError('invalidSpamScore');

        return false;
    }
}
