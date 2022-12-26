<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Forms\CreateForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\BaseEditModal;

class CreateModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->initIds('createModal');
        $this->addForm(new CreateForm());
    }

}