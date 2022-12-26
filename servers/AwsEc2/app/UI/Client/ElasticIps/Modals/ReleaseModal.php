<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Modals;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Forms\DisassociateForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Modals\ModalConfirmDanger;

class Release extends ModalConfirmDanger implements ClientArea
{
    protected $id    = 'releaseModal';
    protected $name  = 'releaseModal';
    protected $title = 'releaseModal';

    public function initContent()
    {
        $this->addForm(new ReleaseForm());
    }
}
