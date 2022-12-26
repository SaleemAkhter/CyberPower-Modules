<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms\InstanceImageForm;

class InstanceImageModal extends \ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstanceImageModal
{
    public function initContent()
    {
        $this->initIds('instanceImageModal');
        $this->initActionButtons();
        $this->addForm(new InstanceImageForm($this));
    }
}