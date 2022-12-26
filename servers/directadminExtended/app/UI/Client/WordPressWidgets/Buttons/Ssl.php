<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class Ssl extends NavSubButton implements ClientArea
{
    protected $id    = 'sslButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-lock-outline';

    public function initContent()
    {
        $this->initLoadModalAction(new SslModal());
    }
}