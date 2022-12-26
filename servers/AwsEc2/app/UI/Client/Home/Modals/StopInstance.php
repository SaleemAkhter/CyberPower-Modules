<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms\Stop;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmDanger;


class StopInstance extends ModalConfirmDanger implements AdminArea, ClientArea
{
    protected $id = 'stopInstance';
    protected $name = 'stopInstance';
    protected $title = 'stopInstanceTitle';

    public function initContent()
    {
        $this->addForm(new Stop());
    }
}