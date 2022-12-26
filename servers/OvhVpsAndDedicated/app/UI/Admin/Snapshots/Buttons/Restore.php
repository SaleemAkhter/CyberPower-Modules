<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals\ConfirmRestore;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDatatableShowModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Restore extends ButtonDatatableShowModal implements ClientArea, AdminArea
{

    protected $id             = 'restoreButton';
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
