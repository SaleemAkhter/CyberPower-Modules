<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Traits;

use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;

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
