<?php


namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;


use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationMassUpgradeModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;

class InstallationMassUpgradeButton extends ButtonMassAction implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-upload';

    public function initContent()
    {
        $this->initIds('installationMassUpgradeButton');
        $this->initLoadModalAction(new InstallationMassUpgradeModal());
    }
}