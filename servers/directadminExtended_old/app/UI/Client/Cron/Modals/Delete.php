<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'deleteModal';
    protected $name  = 'deleteModal';
    protected $title = 'deleteModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\Delete());

    }
}
