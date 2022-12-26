<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\CacheModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class Cache extends NavSubButton implements ClientArea
{
    protected $id   = 'clearCacheButton';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-rotate-left';

    public function initContent()
    {
        $this->initLoadModalAction(new CacheModal());
    }
}