<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Forms;

class BackupSetting extends BaseEditModal implements ClientArea
{
    protected $id    = 'editModal';
    protected $name  = 'editModal';
    protected $title = 'editModal';

    public function initContent()
    {
        $this->addForm(new Forms\BackupSetting());

        if ($this->getRequestValue('actionElementId')) {
            $this->addReplacement();
        }
    }

    public function addReplacement()
    {
        ServiceLocator::call('lang')->addReplacementConstant('name', $this->getRequestValue('actionElementId'));
    }
}
