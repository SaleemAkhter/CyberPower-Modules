<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Forms;

class Upgrade extends BaseEditModal implements AdminArea
{

    protected $id    = 'upgradeModal';
    protected $name  = 'upgradeModal';
    protected $title = 'upgradeModal';

    public function initContent()
    {
        $this->addForm(new Forms\Upgrade())
                ->addInternalAlert('warning');
    }
}
