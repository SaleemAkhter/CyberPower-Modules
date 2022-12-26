<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Parsers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Colors\RgbColor;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\ColorParser;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\ColorCollection\Interfaces\YmlParser;

/**
 * Description of RgbYamlParser
 *
 * @author Tomasz Bielecki <tomasz.bi@modulesgarden.com>
 */
class RgbYmlParser implements ColorParser, YmlParser
{

    public static function parseColors($data = [])
    {
        $tempData = [];

        foreach ($data['colors'] as $id => $color)
        {
            /* add colors to array */
            $tempData[] = new RgbColor($color['r'], $color['g'], $color['b'], $color['a']);
        }

        return $tempData;
    }
}