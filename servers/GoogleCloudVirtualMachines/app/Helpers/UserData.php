<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Lang\Lang;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ModuleConstants;

/**
 * Description of UserData
 *
 * @author Kamil
 */
class UserData {
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
        $allFiles = [0 => Lang::getInstance()->T('Do not use')];
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
            \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }
    }

}
