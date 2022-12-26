<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Others\CronTaskDescription;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CronInformation extends BaseContainer implements ClientArea, AdminArea
{

    protected $id    = 'cronInformation';
    protected $name  = 'cronInformation';
    protected $title = 'cronInformation';

    public function initContent()
    {
        $this->setTitle(Lang::getInstance()->T('cronInformation'));
        $this->addElement(CronTaskDescription::class);
    }

}
