<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

class Generate extends ButtonCreate implements ClientArea
{
    protected $id    = 'generateButton';
    protected $name  = 'generateButton';
    protected $title = 'generateButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\Generate());
    }

}
