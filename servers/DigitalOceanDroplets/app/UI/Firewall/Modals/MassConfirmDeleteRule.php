<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\AddFirewall;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\DeleteRuleForm;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\MassDeleteRuleForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class MassConfirmDeleteRule extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmMassDeleteRuleModal';
    protected $name  = '';
    protected $title = 'confirmMassDeleteRuleModal';

    public function initContent()
    {
        $this->setConfirmButtonDanger();
        $this->addForm(new MassDeleteRuleForm());
    }

}
