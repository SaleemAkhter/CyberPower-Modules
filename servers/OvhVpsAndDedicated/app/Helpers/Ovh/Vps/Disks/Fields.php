<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Formatter\Formatter;

/**
 * Class Fields
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Fields
{
    const TYPE = 'type';
    const STATE = 'state';

    public static function getFieldToFormat()
    {
        return [
            SELF::TYPE => Formatter::UCFIRST,
            SELF::STATE => Formatter::UCFIRST
        ];
    }
}