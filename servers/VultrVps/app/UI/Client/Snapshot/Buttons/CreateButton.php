<?php
namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Buttons;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Modals\CreateModal;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonCreate;

class CreateButton extends ButtonCreate implements ClientArea
{

    public function initContent()
    {
        $this->initIds('createButton');
        $this->initLoadModalAction(new CreateModal());
    }

}