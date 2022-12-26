<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Buttons;

use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebuildConfirm extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id               = 'confirmRebuildButton';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon             = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion
    protected $title            = 'confirmButton';
    protected $customActionName = "rebuildModal";

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Modals\RebuildConfirm());
    }

}
