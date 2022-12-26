<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\PushToLiveModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class PushToLive extends NavSubButton implements ClientArea
{
    protected $id    = 'pushToLiveButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-repeat';

    public function initContent()
    {
        $this->initLoadModalAction(new PushToLiveModal());
    }
}