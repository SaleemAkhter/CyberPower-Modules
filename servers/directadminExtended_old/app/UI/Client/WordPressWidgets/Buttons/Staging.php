<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\StagingModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class Staging extends NavSubButton implements ClientArea
{
    protected $id    = 'stagingButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-forward';

    public function initContent()
    {
        $this->initLoadModalAction(new StagingModal());
    }
}