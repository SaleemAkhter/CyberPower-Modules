<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers\Firewall;
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
class DeleteForm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'deleteFirewallForm';
    protected $name  = 'deleteFirewallForm';
    protected $title = 'deleteFirewallForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Firewall());
        $this->setConfirmMessage('confirmDelete');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
