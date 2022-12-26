<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\CreateModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateButton extends ButtonCreate implements ClientArea, AdminArea
{

    protected $id               = 'createButton';
    protected $title            = 'createButton';

    public function initContent()
    {
        $this->initLoadModalAction(new CreateModal());
    }

}
