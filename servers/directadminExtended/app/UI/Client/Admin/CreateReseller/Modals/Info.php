<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\DomainInfo;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Forms;

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
