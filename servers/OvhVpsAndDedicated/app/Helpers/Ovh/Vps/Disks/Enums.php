<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips\Enums as EnumsIp;

/**
 * Class Enums
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Enums
{
    const CONNECTED = "connected";
    const DISCONNECTED = "disconnected";
    const PENDING = "pending";

    public static function getTypes()
    {
        return EnumsIp::getTypes();
    }

    public static function getStates()
    {
        return [
            SELF::CONNECTED => SELF::CONNECTED,
            SELF::DISCONNECTED => SELF::DISCONNECTED,
            SELF::PENDING => SELF::PENDING,
        ];
    }

    public static function getStatesFormatted()
    {
        $states = self::getStates();
        foreach ($states as &$item)
        {
            $item = ucfirst($item);
        }

        return $states;
    }
}
