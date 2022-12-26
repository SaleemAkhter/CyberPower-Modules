<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Validators;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Lang\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Validators\BaseValidator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Validators\boolen;

/**
 * Class Mac
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Mac extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        if (filter_var($data,FILTER_VALIDATE_MAC) || !$data)
        {
            return true;
        }
        $this->addValidationError(Lang::getLang('correctMacAddress'));

        return false;
    }

}