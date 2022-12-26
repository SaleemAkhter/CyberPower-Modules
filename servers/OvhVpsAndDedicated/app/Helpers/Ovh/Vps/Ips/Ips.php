<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;

/**
 * Class Ips
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ips
{
    public static function formatToDataTable($ips)
    {
        return Formatter::formatForDisplay($ips, Fields::getFieldsToFormat());
    }
}
