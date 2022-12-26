<?php


namespace ModulesGarden\Servers\HetznerVps\App\Helpers\Validators;


class HetznerValidator
{
    const ERROR_MESSAGE = 'HetznerAddon is not installed or activated';

    public static function isInstalled(){

//        if(!class_exists('\ModulesGarden\HetznerAddon\Core\ModuleConstants') ){
//            return false;
//        }
//        return !is_null(\ModulesGarden\HetznerAddon\Core\ModuleConstants::getModuleRootDir());
    }

    public static function isInstalledOrFail(){
//        if(!self::isInstalled()){
//            throw new HetznerAddonNotInstalledException(self::ERROR_MESSAGE);
//        }
    }

    public static function failAsString(){
        return self::ERROR_MESSAGE;
    }


}