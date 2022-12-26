<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class DatabaseName extends BaseValidator
{
    protected $canBeNull = false;

    public function __construct($canBeNull = false)
    {
        $this->canBeNull = $canBeNull;
    }

    protected function validate($data, $additionalData = null)
    {
        $allowed = [".", "-", "_"];
        if ($this->canBeNull && !$data)
        {
            return true;
        }
        if (ctype_alnum(str_replace($allowed, '', $data)))
        {
            return true;
        }

        $this->addValidationError('invalidDatabaseName');

        return false;
    }
}
