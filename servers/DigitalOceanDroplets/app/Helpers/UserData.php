<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Description of UseData
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class UserData
{

    private static function filesDir()
    {
        return ModuleConstants::getModuleRootDir() . DS . 'storage' . DS . 'userDataFiles';
    }

    public static function getFilesNames()
    {
        $files = scandir(self::filesDir(), 1);
        if (count($files) != 0)
        {
            foreach ($files as $key => &$value)
            {
                if ($value === "." || $value === ".." || $value === "index.php")
                {
                    unset($files[$key]);
                }
            }
        }


        return self::preapareArray($files);
    }

    private static function preapareArray($files = [])
    {
        $allFiles = [0 => \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang::getInstance()->T('doNotUse')];
        foreach ($files as $file)
        {
            $allFiles[$file] = $file;
        }
        return $allFiles;
    }

    public static function read($file)
    {
        try
        {
            if (file_exists(self::filesDir() . DS . $file))
            {
                return file_get_contents(self::filesDir() . DS . $file);
            }
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }
    }

}
