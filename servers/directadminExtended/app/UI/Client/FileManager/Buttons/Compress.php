<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;

class Compress extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'compresButton';
    protected $name  = 'compresButton';
    protected $title = 'compresButton';
    protected $class = ['btn btn--sm btn--link btn--icon btn--plain'];
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-markunread-mailbox';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Compress());
    }
}
