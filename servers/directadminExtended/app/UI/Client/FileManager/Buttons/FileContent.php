<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class FileContent extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'fileContentButton';
    protected $name  = 'fileContentButton';
    protected $title = 'fileContentButton';
    protected $class = ['btn btn--sm btn--link btn--icon btn--plain'];
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-open-in-new';
    
    public function initContent()
    {
        $this->showWhere("dataRow.type != 'dir' && dataRow.size < 8184");
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\FileContent());
    }
}
