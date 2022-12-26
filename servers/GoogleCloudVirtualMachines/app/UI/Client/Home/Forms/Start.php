<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;

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
