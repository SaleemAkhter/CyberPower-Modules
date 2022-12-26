<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

class EditApp extends BaseEditModal implements ClientArea
{
    protected $id    = 'editAppModal';
    protected $name  = 'editAppModal';
    protected $title = 'editAppModal';


    public function initContent()
    {
        $this->addForm(new Forms\EditApp());
    }
}