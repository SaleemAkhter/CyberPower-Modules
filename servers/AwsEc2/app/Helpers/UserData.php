<?php

namespace ModulesGarden\Servers\AwsEc2\App\Helpers;


use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;

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

        $allFiles = [0 => sl('lang')->T('doNotUse')];
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
            sl('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }
    }

}
