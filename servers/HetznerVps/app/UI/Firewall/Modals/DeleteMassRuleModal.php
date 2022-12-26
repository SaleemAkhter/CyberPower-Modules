<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\DeleteMassForm;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\DeleteMassRuleForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

class DeleteMassRuleModal extends BaseModal implements ClientArea, AdminArea
{
    protected $id       = 'deleteMassRuleModal';
    protected $name     = 'deleteMassRuleModal';
    protected $title    = 'deleteMassRuleModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new DeleteMassRuleForm());
    }
}