<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class CreateDirectory extends BaseEditModal implements ClientArea
{
    protected $id    = 'createModal';
    protected $name  = 'createModal';
    protected $title = 'createModal';

    public function initContent()
    {
        $this->addForm(new Forms\CreateDirectory());
    }
}
