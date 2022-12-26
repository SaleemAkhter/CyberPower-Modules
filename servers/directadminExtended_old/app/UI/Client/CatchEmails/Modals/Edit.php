<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CatchEmails\Forms\Edit as Form;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class Edit extends BaseModal implements ClientArea
{
    protected $id    = 'editModal';
    protected $name  = 'editModal';
    protected $title = 'editModal';

    public function initContent()
    {
        $this->addForm(new Form());
    }
}