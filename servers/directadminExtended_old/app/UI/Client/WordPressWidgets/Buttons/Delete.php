<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals\DeleteModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationDeleteModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class Delete extends NavSubButton implements ClientArea
{
    protected $id = 'installationDeleteButton';
    protected $class = ['lu-btn lu-btn--danger lu-btn--link lu-btn--plain'];
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->initLoadModalAction(new DeleteModal());
    }
}