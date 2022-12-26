<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\ChangeDomainModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class ChangeDomain extends NavSubButton implements ClientArea
{
    protected $id   = 'changeDomain';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-globe-alt';

    public function initContent()
    {
        $this->initLoadModalAction(new ChangeDomainModal());
    }
}