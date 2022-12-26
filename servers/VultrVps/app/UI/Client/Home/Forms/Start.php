<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;

class Start extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'startForm';
    protected $name = 'startForm';
    protected $title = 'startFormTitle';

    protected $allowedActions = ['start'];

    public function initContent()
    {
        $this->setFormType('start');
        $this->setProvider(new ServiceActions());

        $this->setConfirmMessage('confirmStartInstance');
    }
}
