<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Helper;

class Prefix
{
    public static function get($whmcsUsername)
    {
        if (is_string($whmcsUsername))
        {
            strlen($whmcsUsername) > 8 ? $dbprefix = substr($whmcsUsername, 0, 8) : $dbprefix = $whmcsUsername;

            return $dbprefix . '_';
        }

        return $whmcsUsername;
    }
}
