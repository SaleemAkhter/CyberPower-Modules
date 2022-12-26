<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

/**
 * Class ClassAction
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ClassAction
{
    public static function getLowerCaseClassName($class)
    {
        return lcfirst(self::getClassName($class));
    }

    public static function getReflectionClass($class)
    {
        return  (new \ReflectionClass($class));
    }

    public static function getClassName($class)
    {
        return  self::getReflectionClass($class)->getShortName();
    }
}
