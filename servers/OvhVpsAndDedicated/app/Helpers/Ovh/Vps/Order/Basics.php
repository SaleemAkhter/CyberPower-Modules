<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order;

/**
 * Class Basics
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Basics
{
    CONST LINUX = 'linux';
    CONST WINDOWS = 'windows';

    public static function getSystemNameByVersion($versions)
    {
        if(stripos($versions, 'win', 0) !== false)
        {
            return self::WINDOWS;
        }
        return self::LINUX;
    }
}