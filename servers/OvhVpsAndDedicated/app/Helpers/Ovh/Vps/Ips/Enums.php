<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips;

/**
 * Class Enums
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Enums
{
    CONST IP4 = 'v4';
    CONST IP6 = 'v6';

    CONST ADDITONAL = 'additional';
    CONST PRIMARY = 'primary';

    public static function getVersions()
    {
        return [
            SELF::IP4 => SELF::IP4,
            SELF::IP6 => SELF::IP6
        ];
    }


    public static function getTypes($ucfirst = true)
    {

        return [
            SELF::ADDITONAL => $ucfirst ? ucfirst(SELF::ADDITONAL) : SELF::ADDITONAL,
            SELF::PRIMARY   => $ucfirst ? ucfirst(SELF::PRIMARY) : SELF::PRIMARY
        ];
    }

}