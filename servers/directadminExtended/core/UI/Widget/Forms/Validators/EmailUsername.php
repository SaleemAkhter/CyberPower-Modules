<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators;


class EmailUsername extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $emailUsername = $data;

        if (preg_match('/^[a-zA-Z0-9.+_\-]*$/', $emailUsername)) {
            return true;
        }

        $this->addValidationError('emailUsernameIsInvalid');

        return false;
    }
}