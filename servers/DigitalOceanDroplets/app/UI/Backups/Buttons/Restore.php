<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Modals\ConfirmRestore;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Restore extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'restoreBackupButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion
    protected $title          = 'restoreBackupButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];

    public function initContent()
    {

        $this->initLoadModalAction(new ConfirmRestore());
    }

}
