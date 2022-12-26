<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;

/**
 * Class Fields
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Fields
{
    const TYPE = 'type';
    const VERSION = 'geolocation';

    public static function getFieldsToFormat()
    {
        return [
            SELF::TYPE => Formatter::UCFIRST,
            SELF::VERSION => Formatter::STRTOUPPER,
        ];
    }
}