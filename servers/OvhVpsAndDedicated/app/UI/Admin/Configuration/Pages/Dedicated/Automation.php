<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated\AutomationForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Automation as VpsAutomation;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Automation extends VpsAutomation implements ClientArea, AdminArea
{
    protected $id    = 'automationForm';
    protected $name  = 'automationForm';
    protected $title = 'automationFormTitle';

    public function initContent()
    {
        $this->addElement(new \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated\AutomationForm());
    }

}
