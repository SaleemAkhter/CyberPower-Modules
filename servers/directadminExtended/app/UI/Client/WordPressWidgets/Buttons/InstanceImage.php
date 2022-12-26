<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\InstanceImageModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstanceImage extends NavSubButton implements ClientArea
{
    protected $id    = 'instanceImageButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-group-work';

    public function initContent()
    {
        $this->initLoadModalAction(new InstanceImageModal());
    }
}