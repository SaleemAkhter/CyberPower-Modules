<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class AddDatabase extends BaseEditModal implements ClientArea
{
    protected $id    = 'addModal';
    protected $name  = 'addModal';
    protected $title = 'addModal';

    public function initContent()
    {
        $this->addForm(new Forms\AddDatabase());
    }
}
