<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

class ViewCSR extends ButtonCreate implements ClientArea
{
    protected $id    = 'viewCsrButton';
    protected $name  = 'viewCsrButton';
    protected $title = 'viewCsrButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\ViewCSR());
    }

}
