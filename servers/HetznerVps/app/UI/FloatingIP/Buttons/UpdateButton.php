<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\FloatingIP\Modals\UpdateModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class UpdateButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id = 'floatingIPsEditButton';
    protected $name = 'floatingIPsEditButton';
    protected $title = 'floatingIPsEditButton';
    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon = 'lu-icon lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->initLoadModalAction(new UpdateModal());
    }
}
