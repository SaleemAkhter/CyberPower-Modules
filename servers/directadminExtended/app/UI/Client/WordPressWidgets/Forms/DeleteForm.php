<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Providers\WordPressManagerProvider;
use ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationDeleteForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;

class DeleteForm extends InstallationDeleteForm
{
    public function initContent()
    {
        $this->initIds('deleteForm');
        $this->setFormType('deleteAndRedirect');
        $this->setProvider(new WordPressManagerProvider());
        $this->initFields();
    }
}