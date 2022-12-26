<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Others;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Others\ModuleDescription;

/**
 * Description of CronDescription
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class FirewallDescription extends ModuleDescription implements AdminArea
{

    protected $name          = 'firewallDescription';
    protected $id            = 'firewallDescription';
    protected $title         = 'firewallDescription';

    public function initContent()
    {
        $this->setRaw(true);
        $this->setDescription($this->getCronAction());
    }

    private function getCronAction()
    {
        return 'php -q ' . ModuleConstants::getModuleRootDir() . DS . 'cron' . DS . 'cron.php Firewall';
    }

}
