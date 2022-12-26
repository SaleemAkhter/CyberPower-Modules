<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;


use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationMassDeleteModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;

class InstallationMassDeleteButton extends ButtonMassAction implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->initIds('installationMassDeleteButton');
        $this->initLoadModalAction(new InstallationMassDeleteModal());
        $this->switchToRemoveBtn();
    }
}