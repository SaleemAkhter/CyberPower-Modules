<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core;

/**
 * Class ModuleNamespace
 * @package ModulesGarden\Servers\DigitalOceanDroplets\Core
 */
class ModuleNamespace
{
    /**
     * @param null $namespace
     * @return bool
     */
    public static function validate($namespace = null)
    {
        if(!$namespace)
        {
            return false;
        }

        $namespace  = '\\'.self::fromUnderscore($namespace);

        return stripos($namespace, ModuleConstants::getRootNamespace()) === 0;
    }

    /**
     * @param $namespace
     * @return string|string[]
     */
    public static function toUnderscore($namespace)
    {
        return str_replace("\\", "_", $namespace);
    }

    /**
     * @param $underscore
     * @return string|string[]
     */
    public static function fromUnderscore($underscore)
    {
        return str_replace("_", "\\", $underscore);
    }
}
