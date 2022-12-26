<?php

namespace ModulesGarden\Servers\VultrVps\Core\Traits;

use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\Servers\VultrVps\Core\ServiceLocator;

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
