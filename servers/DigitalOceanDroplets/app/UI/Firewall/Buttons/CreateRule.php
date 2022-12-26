<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals\ConfirmCreateRule;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\AddIconModalButton;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateRule extends AddIconModalButton implements ClientArea, AdminArea
{

    protected $id             = 'createRuleButton';
    protected $title          = 'createRuleButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmCreateRule());
    }

}
