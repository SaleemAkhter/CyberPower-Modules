<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class CreateFileAction extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'createFileActionButton';
    protected $name  = 'createFileActionButton';
    protected $title = 'createFileActionButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\CreateFileAction());
    }
}
