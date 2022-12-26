<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms\AttachForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmSuccess;

class AttachModal extends ModalConfirmSuccess implements ClientArea
{
    protected $id    = 'attachModal';
    protected $name  = 'attachModal';
    protected $title = 'attachModal';

    public function initContent()
    {
        $this->addForm(new AttachForm());
    }
}
