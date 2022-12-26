<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Rename extends BaseEditModal implements ClientArea
{
    protected $id    = 'renameModal';
    protected $name  = 'renameModal';
    protected $title = 'renameModal';

    public function initContent()
    {
        $this->addForm(new Forms\Rename());
    }
}
