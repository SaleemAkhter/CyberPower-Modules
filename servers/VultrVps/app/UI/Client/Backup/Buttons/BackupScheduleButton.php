<?php
namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Buttons;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Modals\BackupScheduleModal;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonCreate;

class BackupScheduleButton extends ButtonCreate implements ClientArea
{

    public function initContent()
    {
        $this->initIds('backupScheduleButton');
        $this->initLoadModalAction(new BackupScheduleModal());
    }

}