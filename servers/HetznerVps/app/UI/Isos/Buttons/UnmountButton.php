<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Modals\UnmountModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class UnmountButton extends ButtonCreate implements ClientArea, AdminArea
{

    protected $id               = 'unmountButton';
    protected $title            = 'unmountButton';
    protected $icon             = 'lu-btn--icon lu-zmdi lu-zmdi-minus-circle-outline';

    public function initContent()
    {
        $this->initLoadModalAction(new UnmountModal());
    }

}