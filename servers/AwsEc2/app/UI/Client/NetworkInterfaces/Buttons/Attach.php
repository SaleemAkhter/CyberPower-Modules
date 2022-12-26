<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Modals\AttachModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class Attach extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'attachButton';
    protected $name  = 'attachButton';
    protected $title = 'attachButton';
    protected $class = ['btn btn-default btn-sm'];
    protected $icon = '';

    public function initContent()
    {
      $this->setShowTitle(true);
       $this->setHideByColumnValue('attached', true);
       $this->initLoadModalAction(new AttachModal());
    }

}
