<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Upload extends BaseEditModal implements ClientArea
{
    protected $id    = 'uploadModal';
    protected $name  = 'uploadModal';
    protected $title = 'uploadModal';

    public function initContent()
    {
        $this->addForm(new Forms\Upload());
    }
}
