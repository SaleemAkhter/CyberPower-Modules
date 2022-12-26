<?php


namespace ModulesGarden\CpanelExtended\App\Configuration\Addon\Update\Patch;

use ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\Servers\AwsEc2\Core\HandlerError\ErrorManager;
use ModulesGarden\Servers\AwsEc2\Core\HandlerError\Logger;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;

class M1M3P4 extends AbstractPatch
{
    public function execute()
    {
        if ($this->runData())
        {
            sl('logger')
                ->addDebug("Correctly installed update {$this->getVersion()} .",[]);
        }
        else
        {
            $errorManager = new ErrorManager(Logger::get());
            $errorManager->addError(self::class,"Incorrectly installed update {$this->getVersion()} .",[]);
        }
    }

}
