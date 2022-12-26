<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Modals\DisassociateModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class Disassociate extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'disassociateButton';
    protected $name  = 'disassociateButton';
    protected $title = 'disassociateButton';
    protected $class = ['btn btn-default btn-sm'];
    protected $icon = '';

    public function initContent()
    {
      $this->setShowTitle(true);
       $this->setHideByColumnValue('associated', false);
       $this->initLoadModalAction(new DisassociateModal());
    }

}
