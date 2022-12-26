<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Validators;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Lang\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Validators\BaseValidator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Validators\boolen;

/**
 * Class IP
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IP extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        if (filter_var($data, FILTER_VALIDATE_IP) || !$data)
        {
            return true;
        }
        $this->loadLang();

        $this->addValidationError($this->lang->translate('correctIPAddress'));

        return false;
    }
}
