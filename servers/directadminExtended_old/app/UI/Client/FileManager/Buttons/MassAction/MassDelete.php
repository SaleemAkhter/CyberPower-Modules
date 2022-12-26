<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

class MassDelete extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massDeleteButton';
    protected $name  = 'massDeleteButton';
    protected $title = 'massDeleteButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->initLoadModalAction(new Modals\MassAction\Delete());
    }
}
