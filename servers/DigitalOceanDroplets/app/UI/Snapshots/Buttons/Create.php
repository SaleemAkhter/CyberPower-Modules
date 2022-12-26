<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Modals\ConfirmCreate;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\AddIconModalButton;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Create extends AddIconModalButton implements ClientArea, AdminArea
{

    protected $id             = 'createButton';
    protected $title          = 'createButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmCreate());
    }

}
