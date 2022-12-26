<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks\Disks;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals\ConfirmRestore;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals\Usage as UsageModal;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Usage extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'restoreBackupButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-view-list';  // cion
    protected $title          = 'restoreBackupButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new UsageModal());
    }

}
