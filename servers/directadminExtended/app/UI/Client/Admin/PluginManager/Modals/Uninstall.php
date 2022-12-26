<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Forms;

class Uninstall extends BaseEditModal implements ClientArea
{
    protected $id    = 'uninstallModal';
    protected $name  = 'uninstallModal';
    protected $title = 'uninstallModal';

    public function initContent()
    {
        $this->addForm(new Forms\Uninstall());
    }
}
