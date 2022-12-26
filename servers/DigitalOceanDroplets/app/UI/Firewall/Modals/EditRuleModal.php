<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals;


use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\EditRuleForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;

class EditRuleModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id    = 'editRuleModal';
    protected $name  = 'editRuleModal';
    protected $title = 'editRule';

    public function initContent()
    {
        $this->addForm(new EditRuleForm());
    }
}