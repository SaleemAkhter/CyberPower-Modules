<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

class ViewKey extends BaseEditModal implements ClientArea
{
    protected $id    = 'viewModal';
    protected $name  = 'viewModal';
    protected $title = 'viewModal';

    public function initContent()
    {
        $this->getActionButtons();
        $this->removeActionButtonByIndex('baseAcceptButton');
        $this->addForm(new Forms\ViewKey());
    }
}
