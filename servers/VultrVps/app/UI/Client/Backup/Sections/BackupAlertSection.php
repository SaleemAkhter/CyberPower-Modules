<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Sections;

use ModulesGarden\Servers\VultrVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Sections\RawSection;

class BackupAlertSection extends RawSection implements ClientArea
{

    public function initContent()
    {
        $this->setInternalAlertMessage("Automatic backups are not enabled");
        $this->setInternalAlertMessageType(AlertTypesConstants::INFO);
    }
}