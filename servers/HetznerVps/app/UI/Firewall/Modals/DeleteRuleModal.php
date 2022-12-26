<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\DeleteRuleForm;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\DeleteModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

class DeleteRuleModal extends DeleteModal implements ClientArea, AdminArea
{
    protected $id       = 'deleteRuleModal';
    protected $name     = 'deleteRuleModal';
    protected $title    = 'deleteRuleModal';

    public function initContent()
    {
        $this->addForm(new DeleteRuleForm());
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
    }
}