<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics;

/**
 * Class Unit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Unit
{
    public static function getUnitFromString($string)
    {
        if(stripos($string, 'g') !== false)
        {
            return 'GB';
        }
        return '';
    }

    public static function getValueWithUnit($string)
    {
        return ((int) $string ) . self::getUnitFromString($string);
    }
}