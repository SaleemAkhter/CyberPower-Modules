<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class CreateEndPoint extends BaseEditModal implements AdminArea
{
    protected $id    = 'createEndPoint';
    protected $name  = 'createEndPoint';
    protected $title = 'createEndPoint';

    public function initContent()
    {
        $this->addForm(new Forms\CreateEndPoint());
    }
}
