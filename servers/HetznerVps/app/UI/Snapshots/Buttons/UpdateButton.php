<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\UpdateModal;
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

    protected $id               = 'updateButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default'];
    protected $icon           = 'lu-btn__icon lu-zmdi lu-zmdi-edit';
    protected $title            = 'updateButton';

    public function initContent()
    {
        $this->initLoadModalAction(new UpdateModal());
    }

}
