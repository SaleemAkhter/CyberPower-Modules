<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

class GenerateCSR extends ButtonCreate implements ClientArea
{
    protected $id    = 'generateCsrButton';
    protected $name  = 'generateCsrButton';
    protected $title = 'generateCsrButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\GenerateCSR());
    }

}
