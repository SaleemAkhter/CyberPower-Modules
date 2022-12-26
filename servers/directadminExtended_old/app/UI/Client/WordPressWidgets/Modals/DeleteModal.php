<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms\DeleteForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationDeleteModal;

class DeleteModal extends InstallationDeleteModal
{
    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm']);
        $this->addForm(new DeleteForm());
    }
}