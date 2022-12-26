<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

/**
 * Class Path
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Path
{
    public static function expandPath($path = null, $expander = null)
    {
        return $path . DS . $expander;
    }

    public static function createPath($pathStart = null, $pathEnd = null)
    {
        return self::createSubPathIfExist($pathStart) . self::createSubPathIfExist($pathEnd);
    }

    public static function createSubPathIfExist($subPath)
    {
        if(!$subPath)
        {
            return '';
        }
        return $subPath[0] != DS ? DS . $subPath : $subPath;
    }
}