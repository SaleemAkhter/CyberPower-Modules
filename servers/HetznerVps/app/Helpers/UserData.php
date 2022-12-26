<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVPS\Core\ModuleConstants;

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


        return self::prepareArray($files);
    }

    private static function prepareArray($files = [])
    {
        $allFiles = [0 => Lang::getInstance()->T('doNotUse')];
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