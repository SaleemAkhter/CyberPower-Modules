<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;


/**
 * Description of UseData
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CallBackButton
{

    public static function run($nameSpace, $index, $params)
    {
        $className = self::preapreClassName($nameSpace);

        if (!class_exists($className))
        {
            \ModulesGarden\Servers\HetznerVps\Core\Helper\json([
                'error' => \ModulesGarden\Servers\HetznerVps\Core\Helper\Lang::getInstance()->T('classNotExist')
            ]);
        }
        $objController = new $className();

        if (!method_exists($objController, $index))
        {
            \ModulesGarden\Servers\HetznerVps\Core\Helper\json([
                'error' => \ModulesGarden\Servers\HetznerVps\Core\Helper\Lang::getInstance()->T('methodNotExist')
            ]);
        }
        return $objController->{$index}($params);
    }

    private static function preapreClassName($nameSpace)
    {
        return "\\" . str_replace("_", "\\", $nameSpace);
    }

}
