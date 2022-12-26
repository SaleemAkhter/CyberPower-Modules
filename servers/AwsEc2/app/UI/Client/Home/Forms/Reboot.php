<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;

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
