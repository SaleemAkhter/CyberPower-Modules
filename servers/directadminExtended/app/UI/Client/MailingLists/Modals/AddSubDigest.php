<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

class AddSubDigest extends BaseEditModal implements ClientArea
{
    protected $id    = 'addSubModal';
    protected $name  = 'addSubModal';
    protected $title = 'addSubModal';

    public function initContent()
    {
        $this->addForm(new Forms\AddSubDigest());
    }
}
