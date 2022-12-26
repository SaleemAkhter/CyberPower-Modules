<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\SL;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\WhmcsVersionComparator;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;

/**
 * Description of Register
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Rewrite extends AbstractReaderYml
{

    protected function load()
    {
        /* This function executes a different code, depending on the version of the container - WHMCS 8 has a much newer version */
        $version8OrHigher = (new WhmcsVersionComparator)->isWVersionHigherOrEqual('8.0.0');

        $dataDev = $this->readYml(ModuleConstants::getFullPath('app', 'Config', 'di', 'rewrite.yml'));
        $data    = [];
        if (isset($dataDev) && isset($dataDev['class']))
        {
            foreach ($dataDev['class'] as $class)
            {
                if($version8OrHigher)
                {
                    /* Adding slashes for for proper functioning if WHMCS8+ */
                    $class['old'] = '\\'.$class['old'];
                    $class['new'] = '\\'.$class['new'];
                }

                if (!isset($data[$class['old']]) && $this->checkInheritance($class['old'], $class['new']))
                {
                    $data[$class['old']] = $class['new'];
                }
            }
        }

        $this->data = $data;
    }

    protected function checkInheritance($old, $new)
    {
        if (is_subclass_of($new, $old))
        {
            return true;
        }
        return false;
    }
}
