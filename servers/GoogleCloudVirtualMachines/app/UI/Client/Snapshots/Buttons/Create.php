<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals\ConfirmCreate;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Create extends ButtonCreate implements ClientArea
{

    protected $id             = 'createButton';
    protected $title          = 'createButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmCreate());
    }

}
