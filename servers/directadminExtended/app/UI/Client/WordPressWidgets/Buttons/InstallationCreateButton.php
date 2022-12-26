<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals\InstallationCreateModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstallationCreateButton extends \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationCreateButton implements ClientArea
{
    public function initContent()
    {
        $this->initIds('installationCreateButton');
        $this->initLoadModalAction(new InstallationCreateModal('baseEditModal'));
    }
}