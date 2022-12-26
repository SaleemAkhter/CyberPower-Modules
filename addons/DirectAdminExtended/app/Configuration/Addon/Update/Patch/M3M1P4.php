<?php
namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Update\Patch;

use ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\DirectAdminExtended\Core\Helper;

/**
 * Description of M1M1P0
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class M3M1P4 extends AbstractPatch
{

    public function execute()
    {
        if ($this->runSchema())
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
