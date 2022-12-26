<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Buttons;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RestoreButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id               = 'restoreButton';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon             = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion
    protected $title            = 'restoreButton';
    protected $customActionName = "mountModal";

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\HetznerVps\App\UI\Backups\Modals\RestoreModal());
    }

}
