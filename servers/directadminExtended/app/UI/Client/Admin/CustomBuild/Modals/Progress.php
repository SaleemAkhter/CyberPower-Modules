<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Pages\DomainInfo;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Forms;

class Progress extends BaseEditModal implements ClientArea
{
    protected $id    = 'progressModal';
    protected $name  = 'progressModal';
    protected $title = 'progressModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--danger submitForm mg-submit-form hidden killprocessbtn']);
        // $this->setIcon('lu-btn__icon lu-zmdi lu-zmdi-delete');
        // $this->removeActionButtonByIndex('baseAcceptButton');
        // $this->removeActionButtonByIndex('baseCancelButton');

        // $this->addElement(new DomainInfo());
        $this->addForm(new Forms\Progress());

    }
}
