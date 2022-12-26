<?php

namespace ModulesGarden\Servers\CpanelExtended\App\UI\Buttons\FileManager;

use \ModulesGarden\Servers\CpanelExtended\Core\UI\Widget\Buttons\ButtonCreate;
use \ModulesGarden\Servers\CpanelExtended\Core\UI\Interfaces\ClientArea;

class CreateFile extends ButtonCreate implements ClientArea
{
    protected $id    = 'createFileButtton';
    protected $name  = 'createFileButtton';
    protected $title = 'createFileButtton';

    public function initContent()
    {
        $this->initLoadModalAction(new \ModulesGarden\Servers\CpanelExtended\App\UI\Modals\FileManager\CreateFile());
    }
}
