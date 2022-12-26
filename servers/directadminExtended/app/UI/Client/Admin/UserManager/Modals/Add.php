<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Forms;

class Add extends BaseEditModal implements ClientArea
{
    protected $id    = 'addModal';
    protected $name  = 'addModal';
    protected $title = 'addModal';

    public function initContent()
    {
        $this->addForm(new Forms\Add());
    }
}
