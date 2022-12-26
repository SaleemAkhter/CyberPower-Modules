<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Copy extends BaseEditModal implements ClientArea
{
    protected $id    = 'copyModal';
    protected $name  = 'copyModal';
    protected $title = 'copyModal';

    public function initContent()
    {
        $this->addForm(new Forms\Copy());
    }
}
