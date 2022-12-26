<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationUpdateModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;


class InstallationUpdateButton extends NavSubButton implements ClientArea
{
    protected $id    = 'UpdateButton';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->initLoadModalAction(new InstallationUpdateModal());
    }
}