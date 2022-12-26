<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals\ConfirmDelete;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Delete extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'deleteIp';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-delete';
    protected $title          = 'delete';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmDelete());
    }

}
