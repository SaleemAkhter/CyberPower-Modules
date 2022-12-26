<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class CopyConfiguration extends BaseEditModal implements AdminArea
{
    protected $id    = 'copyConfigurationModal';
    protected $name  = 'copyConfigurationModal';
    protected $title = 'copyConfigurationModal';

    public function initContent()
    {
        $this->addForm(new Forms\CopyConfiguration());
    }
}
