<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\RuleProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class DeleteRuleForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id       = 'deleteRuleForm';
    protected $name     = 'deleteRuleForm';
    protected $title    = 'deleteRuleForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new RuleProvider())
            ->setConfirmMessage('confirmRuleDelete');

        $rid = (new Hidden('ruleId'))->initIds('ruleId');

        $this->addField($rid);
        $this->loadDataToForm();
    }
}