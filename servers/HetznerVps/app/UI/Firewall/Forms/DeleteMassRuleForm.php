<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\RuleProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;

class DeleteMassRuleForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id       = 'deleteMassRuleForm';
    protected $name     = 'deleteMassRuleForm';
    protected $title    = 'deleteMassRuleForm';

//    protected function getDefaultActions()
//    {
//        return ['deleteMass'];
//    }

    public function initContent()
    {
        $this->setFormType('deleteMass');
        $this->setProvider(new RuleProvider());
        $this->setConfirmMessage('confirmFirewallDelete');
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['deleteMass'];
    }
}