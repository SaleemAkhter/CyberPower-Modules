<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms\DeleteReverseDNSForm;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\DeleteModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

class DeleteReverseDNSModal extends DeleteModal implements ClientArea, AdminArea
{
    protected $id               = 'deleteReverseDNSModal';
    protected $name             = 'deleteReverseDNSModal';
    protected $title            = 'deleteReverseDNSModal';


    public function initContent()
    {
        $this->addForm(new DeleteReverseDNSForm());
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
    }
}