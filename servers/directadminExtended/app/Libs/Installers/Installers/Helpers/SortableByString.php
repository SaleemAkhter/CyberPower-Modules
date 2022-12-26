<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers;

/**
 * Description of SortableByString
 *
 * @author inbs
 */
class SortableByString
{
    public static function sortByObject(&$items, $fieldName)
    {
        usort($items, function ($a, $b) use ($fieldName) {
                return strcmp(strtolower($a->{$fieldName}()), strtolower($b->{$fieldName}()));
        });
    }


}
