<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\AddFirewall;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\DeleteRuleForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmDeleteRule extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmDeleteRuleModal';
    protected $name  = 'confirmDeleteRuleModal';
    protected $title = 'confirmDeleteRuleModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setConfirmButtonDanger();
        $this->addForm(new DeleteRuleForm());
    }

}
