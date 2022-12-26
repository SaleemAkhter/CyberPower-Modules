<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Modals\DeleteModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class Release extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'releaseButton';
    protected $name  = 'releaseButton';
    protected $title = 'releaseButton';
    protected $class = ['btn btn-default btn-sm'];
    protected $icon = '';

    public function initContent()
    {
        $this->setShowTitle(true);
        $this->setHideByColumnValue('associated', true);
        $this->initLoadModalAction(new ReleaseModal());

    }

}
