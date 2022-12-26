<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Buttons;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\DisableButtonByColumnValue;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\BaseDatatableModalButton;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class MountButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    use DisableButtonByColumnValue;

    protected $id               = 'mountButton';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon             = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion
    protected $title            = 'mountButton';
    protected $customActionName = "mountModal";

    public function initContent()
    {
        $this->setDisableByColumnValue('status', true);

        $this->initLoadModalAction(new \ModulesGarden\Servers\HetznerVps\App\UI\Isos\Modals\MountModal());

    }
}
