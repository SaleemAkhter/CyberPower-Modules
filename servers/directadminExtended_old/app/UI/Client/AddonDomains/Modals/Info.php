<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages\DomainInfo;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Forms;

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
