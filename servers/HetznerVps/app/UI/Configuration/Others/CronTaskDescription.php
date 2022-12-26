<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Others;

use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Others\ModuleDescription;

/**
 * Description of CronDescription
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CronTaskDescription extends ModuleDescription implements AdminArea
{

    protected $name          = 'cronTaskDescription';
    protected $id            = 'cronTaskDescription';
    protected $title         = 'cronTaskDescription';

    public function initContent()
    {
        $this->setRaw(true);
        $this->setDescription($this->getCronAction());
        $this->addHtmlAttribute('style', 'width: 98%');
    }

    private function getCronAction()
    {
        return 'php -q ' . ModuleConstants::getModuleRootDir() . DS . 'cron' . DS . 'cron.php Tasks';
    }

}
