<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Forms;

class Update extends BaseEditModal implements ClientArea
{
    protected $id    = 'updateModal';
    protected $name  = 'updateModal';
    protected $title = 'updateModal';

    public function initContent()
    {
        $this->addForm(new Forms\Update());
    }
}
