<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals\ConfirmEdit;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDatatableShowModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Class Edit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Edit extends ButtonDatatableShowModal implements ClientArea, AdminArea
{
    protected $id             = 'editButton';
    protected $icon           = 'lu-zmdi lu-zmdi-edit';  // cion
    protected $title          = 'editButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmEdit());
    }
}
