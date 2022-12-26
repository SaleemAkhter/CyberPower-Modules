<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers\Firewall;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class AddFirewall extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'addSnapshotForm';
    protected $name  = 'addSnapshotForm';
    protected $title = 'addSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Firewall());
        $this->addField((new Text('firewallName'))->setDescription('description'));
        $this->loadDataToForm();
    }

}
