<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;

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
