<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Prepare;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;

/**
 * Class DataFile
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class DataFile
{
    CONST SSD = 'ssd';
    CONST CLOUD = 'cloud';

    public static function getDataWithVpsData($type)
    {
        $path =  ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'vpsData'. DS . 'software.js';
        if(!file_exists($path))
        {
            return;
        }
        $content = file_get_contents($path);
        $content = \json_decode($content, true);

        return isset($content[$type]) ? $content[$type] : $content;
    }
}
