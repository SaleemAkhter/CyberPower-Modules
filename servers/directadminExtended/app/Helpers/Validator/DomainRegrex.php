<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\DomainHelper;

class DomainRegrex extends BaseValidator
{

    protected function validate($data, $additionalData = null)
    {
        preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $data, $output);


        if(!empty($output) && $output[0] == $data)
        {
            return true;
        }
        $this->addValidationError('invalidDomain');

        return false;
    }
}
