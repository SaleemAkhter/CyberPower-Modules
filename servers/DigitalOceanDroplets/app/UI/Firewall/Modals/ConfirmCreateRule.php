<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms\AddFirewallRule;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmCreateRule extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmCreateRuleModal';
    protected $name  = 'confirmCreateRuleModal';
    protected $title = 'confirmCreateRuleModal';

    public function initContent()
    {       
        $this->addForm(new AddFirewallRule());
    }

}
