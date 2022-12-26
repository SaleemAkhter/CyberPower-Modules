<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Modals\AddModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonRedirect;

class Add extends ButtonRedirect implements ClientArea
{
    protected $id    = 'addButton';
    protected $name  = 'addButton';
    protected $title = 'addButton';
    protected $class = ['btn btn-default btn-sm'];
    protected $icon = '';

    public function initContent()
    {
        $this->setShowTitle(true);
    }
}
