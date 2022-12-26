<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Forms;

class Auth extends BaseEditModal implements ClientArea
{
    protected $id    = 'authModal';
    protected $name  = 'authModal';
    protected $title = 'authModal';

    public function initContent()
    {
        $this->addForm(new Forms\Auth());
    }
}
