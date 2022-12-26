<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class EditEndPoint extends BaseEditModal implements AdminArea
{
    protected $id    = 'editEndPoint';
    protected $name  = 'editEndPoint';
    protected $title = 'editEndPoint';

    public function initContent()
    {
        $this->addForm(new Forms\EditEndPoint());
    }
}
