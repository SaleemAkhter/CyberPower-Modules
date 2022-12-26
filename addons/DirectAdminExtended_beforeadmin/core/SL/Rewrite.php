<?php

namespace ModulesGarden\DirectAdminExtended\Core\SL;

use ModulesGarden\DirectAdminExtended\Core\Helper\WhmcsVersionComparator;
use ModulesGarden\DirectAdminExtended\Core\ModuleConstants;

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


                $data[$class['old']] = $class['new'];
            }
        }

        $this->data = $data;
    }
}
