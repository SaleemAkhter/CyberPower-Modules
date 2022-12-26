<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms\Reboot;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Modals\ModalConfirmDanger;

class RebootInstance extends ModalConfirmDanger implements AdminArea, ClientArea
{
    protected $id = 'rebootInstance';
    protected $name = 'rebootInstance';
    protected $title = 'rebootInstanceTitle';

    public function initContent()
    {
        $this->addForm(new Reboot());
    }
}
