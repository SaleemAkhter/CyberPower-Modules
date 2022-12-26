<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Forms;

class Assign extends BaseModal implements ClientArea
{
    protected $id    = 'assignModal';
    protected $name  = 'assignModal';
    protected $title = 'assignModal';

    public function initContent()
    {

        $this->addForm(new Forms\Assign());
    }
}
