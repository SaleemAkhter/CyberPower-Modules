<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class DeleteDatabase extends BaseModal implements ClientArea
{
    protected $id    = 'deleteDatabaseModal';
    protected $name  = 'deleteDatabaseModal';
    protected $title = 'deleteDatabaseModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\DeleteDatabase());
    }
}
