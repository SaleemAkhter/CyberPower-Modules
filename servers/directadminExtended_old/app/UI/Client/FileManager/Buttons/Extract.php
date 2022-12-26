<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class Extract extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'extractButton';
    protected $name  = 'extractButton';
    protected $title = 'extractButton';
    protected $class = ['btn btn--sm btn--link btn--icon btn--plain'];
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-dropbox';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Extract());
    }
}
