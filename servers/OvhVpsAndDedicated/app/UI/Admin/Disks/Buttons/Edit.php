<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals\Edit as EditModal;
/**
 * Class Edit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Edit extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'editDiskButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-edit';
    protected $title          = 'editDiskButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new EditModal());
    }

}