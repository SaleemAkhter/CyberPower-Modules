<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager\MassAction;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class MassCompress extends ButtonMassAction implements ClientArea
{
    protected $id    = 'massCompressButton';
    protected $name  = 'massCompressButton';
    protected $title = 'massCompressButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-markunread-mailbox';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\Compress());
    }
}
