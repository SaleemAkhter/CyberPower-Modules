<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals\Update as UpdateModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Update extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id             = 'updateIp';
    protected $icon           = 'lu-zmdi lu-zmdi-swap';
    protected $title          = 'update';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new UpdateModal());
    }

}
