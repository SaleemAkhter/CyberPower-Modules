<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class UploadAction extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'uploadButton';
    protected $name  = 'uploadButton';
    protected $title = 'uploadButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-upload';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\UploadAction());
    }
}
