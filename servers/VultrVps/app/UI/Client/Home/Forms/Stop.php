<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;

class Stop extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'stopForm';
    protected $name = 'stopForm';
    protected $title = 'stopFormTitle';

    protected $allowedActions = ['stop'];

    public function initContent()
    {
        $this->setFormType('stop');
        $this->setProvider(new ServiceActions());

        $this->setConfirmMessage('confirmStopInstance');
    }
}
