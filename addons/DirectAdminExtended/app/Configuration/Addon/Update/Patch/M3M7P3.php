<?php


namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Update\Patch;


use ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\DirectAdminExtended\Core\Helper;

class M3M7P3 extends AbstractPatch
{
    public function execute()
    {
        logActivity(self::class,"Correctly installed update {$this->getVersion()} .");
        if ($this->runSchema())
        {


            Helper\sl('logger')
                ->addDebug("Correctly installed update {$this->getVersion()} .",[]);
        }
        else
        {
            logActivity(self::class,"Incorrectly installed update {$this->getVersion()} .");
            Helper\sl('"errorManager"')
                ->addError(self::class,"Incorrectly installed update {$this->getVersion()} .",[]);
        }
    }
}
