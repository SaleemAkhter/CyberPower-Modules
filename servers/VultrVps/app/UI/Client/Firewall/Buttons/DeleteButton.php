<?php
namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Buttons;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Firewall\Modals\DeleteModal;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class DeleteButton extends ButtonDataTableModalAction implements ClientArea
{

    public function initContent()
    {
        $this->initIds('deleteButton');
        $this->switchToRemoveBtn();
        $this->initLoadModalAction(new DeleteModal());
    }

}