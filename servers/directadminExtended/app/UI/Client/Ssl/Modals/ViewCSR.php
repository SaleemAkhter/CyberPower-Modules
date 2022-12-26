<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;

class ViewCSR extends BaseModal implements ClientArea
{
    protected $id    = 'viewCsr';
    protected $name  = 'viewCsr';
    protected $title = 'viewCsr';

    public function initContent()
    {
        $this->addForm(new Forms\ViewCSR());

        $this->setModalSizeMedium();
        $this->setModalTitleTypeSuccess();
        $this->getActionButtons();
        $this->removeActionButtonByIndex('baseAcceptButton');


    }
}
