<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

/**
 * Description of DatePicker
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DatePicker extends BaseField
{
    const FORMAT_DD_MM_YYYY_SLASH   = "DD/MM/YYYY";
    const FORMAT_DD_MM_YYYY_DOT     = "DD.MM.YYYY";
    const FORMAT_DD_MM_YYYY_DASH    = "DD-MM-YYYY";
    const FORMAT_MM_DD_YYYY_SLASH   = "MM/DD/YYYY";
    const FORMAT_YYYY_MM_DD_SLASH   = "YYYY/MM/DD";
    const FORMAT_YYYY_MM_DD_DASH    = "DD-MM-YYYY";

    protected $id           = 'datePicker';
    protected $name         = 'datePicker';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-date-picker';

    protected $dateFormat = self::FORMAT_DD_MM_YYYY_DASH;

    public function setDateFormat($dateFormat)
    {
        if(trim($dateFormat) !== '' && is_string($dateFormat))
        {
            $this->dateFormat = $dateFormat;
        }

        return $this;
    }

    public function getDateFormat()
    {
        return $this->dateFormat;
    }




}
