<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Update\Patch;

use ModulesGarden\WordpressManager\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\WordpressManager\Core\Helper;

class M1M7P1 extends AbstractPatch
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
