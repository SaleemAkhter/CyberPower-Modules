<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers\FirewallRule;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DeleteRuleForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'deleteRuleFirewallForm';
    protected $name  = 'deleteRuleFirewallForm';
    protected $title = 'deleteRuleFirewallForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new FirewallRule());
        $this->setConfirmMessage('confirmDelete');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
