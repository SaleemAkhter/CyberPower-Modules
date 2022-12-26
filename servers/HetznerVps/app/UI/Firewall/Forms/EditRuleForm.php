<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\RuleProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;

class EditRuleForm extends AddRuleForm implements ClientArea, AdminArea
{

    protected $id       = 'editRuleForm';
    protected $name     = 'editRuleForm';
    protected $title    = 'editRuleForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new RuleProvider());

        $rid = (new Hidden('ruleId'))->initIds('ruleId');
        $section = new RawSection('section');

        $section->addField($rid);
        $this->addSection($section);
        $this->loadDataToForm();
        $this->reloadFormStructure();
    }
}