<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators\BaseValidator;

class Url extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $pattern = '@^((?!http|https).)[^\s/$%.?#].[^\s]*$@iS';

        $match = preg_match($pattern, $data);

        if($match == '0' || $match == false)
        {
            $this->addValidationError('wrongUrl');
            return false;
        }
    }
}