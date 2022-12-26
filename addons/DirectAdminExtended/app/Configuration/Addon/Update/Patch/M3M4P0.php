<?php


namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Update\Patch;


use ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\DirectAdminExtended\Core\Helper;

class M3M4P0 extends AbstractPatch
{
    public function execute()
    {
        if ($this->runData())
        {
            Helper\sl('logger')
                ->addDebug("Correctly installed update {$this->getVersion()} .",[]);
        }
        else
        {
            Helper\sl('"errorManager"')
                ->addError(self::class,"Incorrectly installed update {$this->getVersion()} .",[]);
        }
    }
}