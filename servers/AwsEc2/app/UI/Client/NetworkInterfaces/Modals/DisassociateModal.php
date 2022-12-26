<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms\DisassociateForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmDanger;

class DisassociateModal extends ModalConfirmDanger implements ClientArea
{
    protected $id    = 'disassociateModal';
    protected $name  = 'disassociateModal';
    protected $title = 'disassociateModal';

    public function initContent()
    {
        $this->addForm(new DisassociateForm());
    }
}
