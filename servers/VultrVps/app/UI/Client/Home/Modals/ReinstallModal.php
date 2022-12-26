<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms\Reboot;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms\ReinstallForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\ModalConfirmDanger;

class ReinstallModal extends ModalConfirmDanger implements AdminArea, ClientArea
{

    public function initContent()
    {
        $this->initIds('reinstallModal');
        $this->addForm(new ReinstallForm());
    }
}
