<?php

namespace ModulesGarden\Servers\HetznerVps\Core\DependencyInjection;

/**
 * Class DependencyInjection
 * @package ModulesGarden\Servers\HetznerVps\Core\DependencyInjection
 */
class DependencyInjection
{
    /**
     * @param null $className
     * @param null $methodName
     * @param bool $canClone
     * @return mixed
     */
    public static function get($className = null, $methodName = null, $canClone = true)
    {
        if($methodName)
        {
            return Container::getInstance()->call("$className@$methodName");
        }

        return Container::getInstance()->make($className);
    }

    /**
     * @param null $className
     * @param null $methodName
     * @param bool $canClone
     * @return mixed
     */
    public static function create($className = null, $methodName = null, $canClone = true)
    {
        if($methodName)
        {
            return Container::getInstance()->call("$className@$methodName");
        }

        return Container::getInstance()->make($className);
    }

    /**
     * @param null $className
     * @param null $methodName
     * @return mixed
     */
    public static function call($className = null, $methodName = null)
    {
        if($methodName)
        {
            return Container::getInstance()->call("$className@$methodName");
        }

        return Container::getInstance()->make($className);
    }
}