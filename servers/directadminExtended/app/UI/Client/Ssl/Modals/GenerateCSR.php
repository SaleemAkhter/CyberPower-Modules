<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

class GenerateCSR extends BaseEditModal implements ClientArea
{
    protected $id    = 'generateModal';
    protected $name  = 'generateModal';
    protected $title = 'generateModal';

    public function initContent()
    {
        $this->addForm(new Forms\GenerateCSR());
    }
}
