<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms\ImportForm;

class ImportModal extends \ModulesGarden\WordpressManager\App\UI\Installations\Modals\ImportModal
{
    public function initContent()
    {
        $this->initIds('importModal');
        $this->addForm(new ImportForm());
    }
}