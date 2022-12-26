<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationUpdateModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class ManageAutoUpgrade extends ButtonMassAction implements ClientArea
{
    protected $id   = 'manageAutoUpgradeButton';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->initLoadModalAction(new InstallationUpdateModal());
    }
}