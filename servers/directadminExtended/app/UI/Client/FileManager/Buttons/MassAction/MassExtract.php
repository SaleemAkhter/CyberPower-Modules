<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager\MassAction;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class MassExtract extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massExtractButton';
    protected $name  = 'massExtractButton';
    protected $title = 'massExtractButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-dropbox';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Extract());
    }
}
