<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Forms;

class Removefromreseller extends BaseModal implements ClientArea
{
    protected $id    = 'removefromresellerModal';
    protected $name  = 'removefromresellerModal';
    protected $title = 'removefromresellerModal';

    public function initContent()
    {

        $this->addForm(new Forms\Removefromreseller());
    }
}
