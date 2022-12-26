<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\CloneModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class CloneButton extends NavSubButton implements ClientArea
{
    protected $id    = 'cloneButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus-circle-o-duplicate';

    public function initContent()
    {
        $this->initLoadModalAction(new CloneModal());
    }
}