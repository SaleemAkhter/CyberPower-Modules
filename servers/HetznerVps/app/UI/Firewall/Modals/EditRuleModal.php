<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\EditRuleForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class EditRuleModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id       = 'editRuleModal';
    protected $name     = 'editRuleModal';
    protected $title    = 'editRuleModal';

    public function initContent()
    {
        $this->addForm(new EditRuleForm());
    }
}