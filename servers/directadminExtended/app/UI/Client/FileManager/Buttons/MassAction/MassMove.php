<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager\MassAction;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class MassMove extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massMoveButton';
    protected $name  = 'massMoveButton';
    protected $title = 'massMoveButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-redo';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Move());
    }
}
