<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms;

class EditLimits extends BaseEditModal implements ClientArea
{
    protected $id    = 'editLimitsModal';
    protected $name  = 'editLimitsModal';
    protected $title = 'editLimitsModal';

    public function initContent()
    {
        $this->addForm(new Forms\EditLimits());
    }
}
