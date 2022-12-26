<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Modals\ConfirmRestore;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Modals\ChangeHostnameModal;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Helpers\Server;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;

/**
 * Description of ChangeHostname
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ChangeHostname extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id             = 'changeHostnameButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon           = 'lu-zmdi lu-zmdi-edit';
    protected $title          = 'changeHostnameButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];

    public function initContent()
    {

        $this->initLoadModalAction(new ChangeHostnameModal());
    }


}
