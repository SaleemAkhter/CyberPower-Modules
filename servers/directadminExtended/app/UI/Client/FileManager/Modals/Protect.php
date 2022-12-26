<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Protect extends BaseEditModal implements ClientArea
{
    protected $id    = 'protectModal';
    protected $name  = 'protectModal';
    protected $title = 'protectModal';

    public function initContent()
    {
        $this->addForm(new Forms\Protect());
    }
}