<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Validators;


class Date extends BaseValidator
{
    protected function validate($data, $additionalData = null)
    {
        $start = strtotime(str_replace('/', '-', $additionalData->request->get('formData')['start']));
        $end = strtotime(str_replace('/', '-', $data ));

        if ($start >= $end) {
            $this->addValidationError('theseDatesAreNotValid');

            return false;
        }

        return true;
    }
}