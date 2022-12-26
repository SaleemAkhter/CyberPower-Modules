<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals\ConfirmCreate;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals\ConfirmDelete;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\AddIconModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Delete extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'deleteButton';
    protected $title          = 'deleteButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center'];
    protected $icon           = 'lu-zmdi lu-zmdi-delete';  // cion
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip'
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmDelete());
    }

}
