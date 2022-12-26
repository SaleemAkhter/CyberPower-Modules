<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

class DeleteFirewallForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id               = 'deleteFirewallForm';
    protected $name             = 'deleteFirewallForm';
    protected $title            = 'deleteFirewallForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new FirewallProvider())
            ->setConfirmMessage('confirmFirewallDelete');

        $rid = (new Hidden('id'))->initIds('id');

        $this->addField($rid);
        $this->loadDataToForm();
    }
}