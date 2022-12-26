<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;

class EditFirewallForm extends AddFirewallForm implements ClientArea, AdminArea
{

    protected $id               = 'editFirewallForm';
    protected $name             = 'editFirewallForm';
    protected $title            = 'editFirewallForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new FirewallProvider());

        $rid = (new Hidden('ruleId'))->initIds('ruleId');
        $section = new RawSection('section');

        $section->addField($rid);
        $this->addSection($section);
        $this->loadDataToForm();
        $this->reloadFormStructure();
    }
}