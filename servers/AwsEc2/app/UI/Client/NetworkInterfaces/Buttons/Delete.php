<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Modals\DeleteModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class Delete extends \ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Buttons\ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';
    protected $icon = 'lu-btn__icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->setShowTitle(false);
        $this->initLoadModalAction(new DeleteModal());
    }

}
