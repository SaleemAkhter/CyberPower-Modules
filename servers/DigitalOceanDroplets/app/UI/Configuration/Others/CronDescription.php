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
class CronDescription extends ModuleDescription implements AdminArea
{

    protected $name          = 'cronDescription';
    protected $id            = 'cronDescription';
    protected $title         = 'cronDescription';

    public function initContent()
    {
        $this->setRaw(true);
        $this->setDescription($this->getCronAction());
    }

    private function getCronAction()
    {
        return 'php -q ' . ModuleConstants::getModuleRootDir() . DS . 'cron' . DS . 'cron.php MailCron';
    }

}
