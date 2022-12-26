<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals\InstanceImageModal;

class InstanceImageButton extends \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstanceImageButton
{
    public function initContent()
    {
        $this->initIds('InstanceImageButton');
        $this->initLoadModalAction(new InstanceImageModal());
    }
}