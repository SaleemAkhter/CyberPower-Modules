<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

class CreateDirectory extends ButtonCreate implements ClientArea
{
    protected $id    = 'createDirectoryButton';
    protected $name  = 'createDirectoryButton';
    protected $title = 'createDirectoryButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\CreateDirectory());
    }
}
