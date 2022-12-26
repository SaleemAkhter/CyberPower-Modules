<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages\DomainInfo;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Forms;

class Info extends BaseEditModal implements ClientArea
{
    protected $id    = 'infoModal';
    protected $name  = 'infoModal';
    protected $title = 'infoModal';

    public function initContent()
    {
        $this->removeActionButtonByIndex('baseAcceptButton');
        $this->addElement(new DomainInfo());
    }
}
