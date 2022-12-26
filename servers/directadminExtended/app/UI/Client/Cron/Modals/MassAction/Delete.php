<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'deleteMassModal';
    protected $name  = 'deleteMassModal';
    protected $title = 'deleteMassModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Forms\MassAction\Delete());

    }
}
