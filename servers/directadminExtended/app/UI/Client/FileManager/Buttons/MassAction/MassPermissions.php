<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

class MassPermissions extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massPermissionsButton';
    protected $name  = 'massPermissionsButton';
    protected $title = 'massPermissionsButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-key';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\MassAction\Permissions());
    }
}
