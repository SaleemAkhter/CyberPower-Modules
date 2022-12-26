<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class DeleteEndPoint extends BaseModal implements AdminArea
{
    protected $id    = 'deleteEndPoint';
    protected $name  = 'deleteEndPoint';
    protected $title = 'deleteEndPoint';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger()->setModalTitleTypeDanger();
        $this->addForm(new Forms\DeleteEndPoint());
    }
}
