<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class Move extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'moveButton';
    protected $name  = 'moveButton';
    protected $title = 'moveButton';
    protected $class = ['btn btn--sm btn--link btn--icon btn--plain'];
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-redo';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Move());
    }
}
