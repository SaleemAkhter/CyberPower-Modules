<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Update\Patch;

use ModulesGarden\WordpressManager\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\WordpressManager\Core\Helper;

/**
 * Description of M1M1P0
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com> 
 */
class M1M4P0 extends AbstractPatch
{

    public function execute()
    {
        if ($this->runSchema())
        {
            Helper\sl('logger')
                    ->addDebug("Correctly installed update {$this->getVersion()} .", []);
        }
        else
        {
            Helper\sl('"errorManager"')
                    ->addError(self::class, "Incorrectly installed update {$this->getVersion()} .", []);
        }
    }
}
