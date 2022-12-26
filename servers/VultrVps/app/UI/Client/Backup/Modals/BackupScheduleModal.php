<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Forms\BackupScheduleForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\BaseEditModal;

class BackupScheduleModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->initIds('backupScheduleModal');
        $this->addForm(new BackupScheduleForm());
    }

}