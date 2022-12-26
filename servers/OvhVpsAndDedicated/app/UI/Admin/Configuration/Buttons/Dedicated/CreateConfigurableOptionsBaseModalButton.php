<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Modals\Dedicated\CreateConfigurableOptionsConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons\CreateConfigurableOptionsBaseModalButton as DedicatedCreateConfigurableOptionsBaseModalButton;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CreateConfigurableOptionsBaseModalButton extends DedicatedCreateConfigurableOptionsBaseModalButton implements AdminArea
{

    public function initContent()
    {
        $this->initLoadModalAction(new CreateConfigurableOptionsConfirm());
    }

}
