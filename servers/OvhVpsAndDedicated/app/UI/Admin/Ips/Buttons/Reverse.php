<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals\ReverseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDatatableShowModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Class Reverse
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reverse extends ButtonDatatableShowModal implements ClientArea, AdminArea
{
    protected $id             = 'reverseIp';
    protected $icon           = 'lu-zmdi lu-zmdi-swap';
    protected $title = 'reverseIp';

    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ReverseModal());
    }
}