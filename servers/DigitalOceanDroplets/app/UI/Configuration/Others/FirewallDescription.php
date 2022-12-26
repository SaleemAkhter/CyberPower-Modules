<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Others;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Others\ModuleDescription;

/**
 * Description of CronDescription
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
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
