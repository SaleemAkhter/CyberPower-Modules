<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;

class AddFirewallForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id = 'firewallAddForm';
    protected $name = 'firewallAddForm';
    protected $title = 'firewallAddForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new FirewallProvider());

        $this->loadDataToForm();
        $this->reloadFormStructure();
    }

    public function mainSection()
    {
        $mainSection = (new RawSection('mainRawSection'));

        $name = (new Text('name'))
            ->initIds('name')
            ->notEmpty()
        ;

        $applyTo = (new Hidden('applyTo'))
            ->initIds('applyTo')
            ->setDefaultValue('on')
            ->setValue('on')
        ;

        $mainSection
            ->addField($applyTo)
            ->addField($name);

        return $mainSection;
    }

    public function reloadFormStructure()
    {
        $this->addSection($this->mainSection());
        $this->loadDataToForm();
    }
}