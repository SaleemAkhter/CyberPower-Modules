<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;


class ConfirmPassword extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $password = $additionalData->request->get('formData')['password'];
        $repeatedPassword = $data;

        if ($password != $repeatedPassword) {
            $this->addValidationError('repeatedPasswordIsDifferent');

            return false;
        }

        return true;
    }
}
