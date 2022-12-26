<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Download;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\PermissionsEncode;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions\PermissionsDecode;

class Download
{

    public static function prepare($fileName, $fileContent)
    {
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="'. $fileName.'"');
        header('Cache-Control: no-store, no-cache');


        echo $fileContent;
        exit();


    }
}
