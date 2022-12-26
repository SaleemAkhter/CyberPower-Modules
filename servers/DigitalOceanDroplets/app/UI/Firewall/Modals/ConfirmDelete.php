<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\AddFirewall;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\DeleteForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmDelete extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmDeleteModal';
    protected $name  = 'confirmDeleteModal';
    protected $title = 'confirmDeleteModal';

    public function initContent()
    {
        $this->setConfirmButtonDanger();
        $this->addForm(new DeleteForm());
    }

}
