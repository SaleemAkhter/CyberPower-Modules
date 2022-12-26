<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;

class Reset extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'resetForm';
    protected $name = 'resetForm';
    protected $title = 'resetForm';

    protected $allowedActions = ['reset'];

    public function initContent()
    {
        $this->setFormType('reset');
        $this->setProvider(new ServiceActions());

        $this->setConfirmMessage('confirmResetInstance');
    }
}
