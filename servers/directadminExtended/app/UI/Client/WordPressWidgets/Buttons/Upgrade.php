<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationUpgradeModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class Upgrade extends NavSubButton implements ClientArea
{
    protected $id    = 'UpgradeButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-upload';

    public function initContent()
    {
        $this->initLoadModalAction(new InstallationUpgradeModal());
    }
}