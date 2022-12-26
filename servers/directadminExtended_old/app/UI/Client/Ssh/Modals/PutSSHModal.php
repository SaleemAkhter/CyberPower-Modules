<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;

class PutSSHModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'putModal';
    protected $name  = 'putModal';
    protected $title = 'putModal';

    public function initContent()
    {
        $this->addForm(new Forms\PutSSHForm());
    }
}