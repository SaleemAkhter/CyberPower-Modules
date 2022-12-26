<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators;


class RepeatedPassword extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $password = $additionalData->request->get('formData')['options']['adminPasswd'];
        $repeatedPassword = $data;

        if ($password != $repeatedPassword) {
            $this->addValidationError('repeatedPasswordIsDifferent');

            return false;
        }

        return true;
    }
}