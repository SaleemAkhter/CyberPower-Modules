<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Modals\AddModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonCreate;

class Add extends ButtonCreate implements ClientArea
{
    protected $id    = 'addButton';
    protected $name  = 'addButton';
    protected $title = 'addButton';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initLoadModalAction(new AddModal());
    }
}