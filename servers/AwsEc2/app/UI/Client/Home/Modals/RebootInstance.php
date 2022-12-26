<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Forms\Reboot;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmDanger;

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
