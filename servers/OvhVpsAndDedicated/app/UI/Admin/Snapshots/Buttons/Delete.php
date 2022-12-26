<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals\ConfirmDelete;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Delete extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id             = 'deleteButton';
    protected $icon           = 'lu-zmdi lu-zmdi-delete';  // cion
    protected $title          = 'deleteButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->initLoadModalAction(new ConfirmDelete());
    }

}
