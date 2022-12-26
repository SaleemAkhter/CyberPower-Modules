<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Forms;

class Activate extends BaseEditModal implements ClientArea
{
    protected $id    = 'activateModal';
    protected $name  = 'activateModal';
    protected $title = 'activateModal';

    public function initContent()
    {
        $this->addForm(new Forms\Activate());
    }
}
