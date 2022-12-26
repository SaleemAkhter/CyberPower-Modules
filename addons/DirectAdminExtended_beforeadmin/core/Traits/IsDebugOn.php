<?php

namespace ModulesGarden\DirectAdminExtended\Core\Traits;

use ModulesGarden\DirectAdminExtended\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;

/**
 * Description of IsDebugOn
 *
 * @author INBSX-37H
 */
trait IsDebugOn
{
    protected $isDebug = null;    
    
    public function isDebugOn()
    {   
        if ($this->isDebug === null)
        {
            $addon = ServiceLocator::call(Config::class);
        
            $this->isDebug =  (bool)((int)$addon->getConfigValue('debug', "0"));
        }
        
        return $this->isDebug;
    }
}
