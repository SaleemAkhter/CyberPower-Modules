<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

class CloneApp extends BaseEditModal implements ClientArea
{
    protected $id    = 'cloneAppModal';
    protected $name  = 'cloneAppModal';
    protected $title = 'cloneAppModal';


    public function initContent()
    {
        $this->addForm(new Forms\CloneApp());
    }
}