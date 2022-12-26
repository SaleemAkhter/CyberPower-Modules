<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager\MassAction;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class MassCopy extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massCopyButton';
    protected $name  = 'massCopyButton';
    protected $title = 'massCopyButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-copy';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Copy());
    }
}
