<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Others\CronDescription;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Others\CronTaskDescription;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Others\FirewallDescription;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
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
        $this->addElement(CronDescription::class);
        $this->addElement(CronTaskDescription::class);
        $this->addElement(FirewallDescription::class);
    }

}
