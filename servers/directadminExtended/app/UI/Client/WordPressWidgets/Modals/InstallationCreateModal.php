<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms\InstallationCreateForm;

class InstallationCreateModal extends \ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationCreateModal
{
    public function initContent()
    {
        $this->initActionButtons();
        $this->addForm(new InstallationCreateForm($this));
    }
}