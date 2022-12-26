<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Lang\Lang;

/**
 * Class Data
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Data
{
    public static function prepareTemplates($templates)
    {
        $out = [];
        asort($templates);
        foreach ($templates as $template)
        {
            $out[$template] = $template;
        }

        return $out;
    }
}