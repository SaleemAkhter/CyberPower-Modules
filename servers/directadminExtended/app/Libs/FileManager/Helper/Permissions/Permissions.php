<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\PermissionsEncode;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\PermissionsDecode;

class Permissions
{
    private static $encodeInstance;
    private static $decodeInstance;

    public static function getEncodeInstance()
    {
        if (!is_null(self::$encodeInstance))
        {
            return self::$encodeInstance;
        }

        return new PermissionsEncode();
    }

    public static function getDecodeInstance()
    {
        if (!is_null(self::$decodeInstance))
        {
            return self::$decodeInstance;
        }

        return new PermissionsDecode();
    }
}
