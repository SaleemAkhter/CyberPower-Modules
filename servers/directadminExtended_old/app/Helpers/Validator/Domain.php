<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\DomainHelper;

class Domain extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        $domain = new DomainHelper($data);

        if ($domain->isValid())
        {
            return true;
        }

        $this->addValidationError('invalidDomain');

        return false;
    }
}
