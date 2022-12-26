<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;

class ApplyFirewallForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id       = 'applyFirewallForm';
    protected $name     = 'applyFirewallForm';
    protected $title    = 'applyFirewallForm';

    public function initContent()
    {
        $this->setFormType('applyFirewallToServer')
            ->setProvider(new FirewallProvider());

        $id = (new Hidden('id'))->initIds('id');
        $this->addField($id);
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['applyFirewallToServer'];
    }
}