<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

trait ClassNameComponent
{
    protected function getClassName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
    
    protected function getClassNameWithNamespace()
    {
        return __CLASS__;
    }
}
