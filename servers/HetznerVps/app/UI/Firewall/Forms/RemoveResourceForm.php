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

class RemoveResourceForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id       = 'removeResourceForm';
    protected $name     = 'removeResourceForm';
    protected $title    = 'removeResourceForm';

    public function initContent()
    {
        $this->setFormType('removeFromResource')
            ->setProvider(new FirewallProvider());

        $id = (new Hidden('id'))->initIds('id');
        $this->addField($id);
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['removeFromResource'];
    }
}