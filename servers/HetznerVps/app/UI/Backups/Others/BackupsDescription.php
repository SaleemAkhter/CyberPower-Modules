<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Others;

use ModulesGarden\Servers\HetznerVps\Core\ModuleConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Others\ModuleDescription;

/**
 * Description of CronDescription
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsDescription extends ModuleDescription implements AdminArea
{

    protected $name          = 'cronTaskDescription';
    protected $id            = 'cronTaskDescription';
    protected $title         = 'cronTaskDescription';

    public function initContent()
    {
        $this->setDescription('desc');
    }

    public static function getClass() {
        return BackupsDescription::class;
    }

}
