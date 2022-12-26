<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Snapshot\Forms\UpdateForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\BaseEditModal;

class UpdateModal extends BaseEditModal implements ClientArea
{

    public function initContent()
    {
        $this->initIds('updateModal');
        $this->addForm(new UpdateForm());
    }

}