<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\DeleteModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DeleteButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id               = 'deleteButton';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain'];
    protected $icon             = 'lu-btn__icon lu-zmdi lu-zmdi-delete';
    protected $title            = 'deleteButton';

    public function initContent()
    {
        $this->initLoadModalAction(new  DeleteModal());
    }

}
