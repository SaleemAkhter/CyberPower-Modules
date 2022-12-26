<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ModalTabsEdit;

class LetsEncrypt extends ModalTabsEdit implements ClientArea
{
    protected $id    = 'letsEncryptModal';
    protected $name  = 'letsEncryptModal';
    protected $title = 'letsEncryptModal';

    public function initContent()
    {
        $this->addForm(new Forms\LetsEncrypt());
    }
}
