<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals\ConfirmRestore;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Restore extends ButtonDataTableModalAction implements ClientArea
{

    protected $id             = 'restoreButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion
    protected $title          = 'restoreButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmRestore());
    }

}
