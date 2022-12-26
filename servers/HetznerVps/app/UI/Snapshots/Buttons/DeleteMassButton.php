<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\DeleteMassModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonMassAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DeleteMassButton extends ButtonMassAction implements ClientArea, AdminArea
{

    protected $id               = 'deleteMassButton';
    protected $name             = 'deleteMassButton';
    protected $title            = 'deleteMassButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->initLoadModalAction(new DeleteMassModal());
    }

}
