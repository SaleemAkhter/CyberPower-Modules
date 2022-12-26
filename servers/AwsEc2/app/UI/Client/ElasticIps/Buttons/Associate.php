<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Modals\DeleteModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class Associate extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonRedirect implements ClientArea
{
    protected $id    = 'associateButton';
    protected $name  = 'associateButton';
    protected $title = 'associateButton';
    protected $class = ['btn btn-default btn-sm'];
    protected $icon = '';

    public function initContent()
    {
        $this->setShowTitle(true);
        $this->setHideByColumnValue('associated', true);
        $this->setRawUrl(BuildUrl::getUrl('elasticIps', 'associate', ['id'=>$_GET['id']]))->setRedirectParams(['actionElementId'=>':id']);

    }

}
