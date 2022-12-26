<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;

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
