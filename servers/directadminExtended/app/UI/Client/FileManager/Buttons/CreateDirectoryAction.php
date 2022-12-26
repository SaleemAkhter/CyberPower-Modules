<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class CreateDirectoryAction extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'createDirectoryButton';
    protected $name  = 'createDirectoryButton';
    protected $title = 'createDirectoryButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\CreateDirectoryAction());
    }
}
