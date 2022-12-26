<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;

class Reboot extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'rebootForm';
    protected $name = 'rebootForm';
    protected $title = 'rebootFormTitle';

    protected $allowedActions = ['reboot'];

    public function initContent()
    {
        $this->setFormType('reboot');
        $this->setProvider(new ServiceActions());

        $this->setConfirmMessage('confirmRebootInstance');
    }
}
