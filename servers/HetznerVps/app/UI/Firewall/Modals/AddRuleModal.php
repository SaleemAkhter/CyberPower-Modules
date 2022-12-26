<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\AddRuleForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class AddRuleModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id       = 'addRuleModal';
    protected $name     = 'addRuleModal';
    protected $title    = 'addRuleModal';

    public function initContent()
    {
        $this->addForm(new AddRuleForm());
    }
}