<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms\Start;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\ModalConfirmSuccess;

class StartInstance extends ModalConfirmSuccess implements AdminArea, ClientArea
{
    protected $id = 'startInstance';
    protected $name = 'startInstance';
    protected $title = 'startInstanceTitle';

    public function initContent()
    {
        $this->addForm(new Start());
    }
}