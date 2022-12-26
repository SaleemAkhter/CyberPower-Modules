<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Modals\CreateConfigurableOptionsConfirm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateConfigurableOptionsBaseModalButton extends ButtonCreate implements AdminArea {

    protected $id             = 'createCOBaseModalButton'; // atrybut id w tag-u
    protected $name           = 'createCOBaseModalButton'; // atrybut name w tagu
    protected $title          = 'createCOBaseModalButton';
    protected $class          = ['lu-btn lu-btn--success'];
    protected $htmlAttributes = [
        'href' => 'javascript:;',
    ];

    public function initContent() {
        $this->initLoadModalAction(new CreateConfigurableOptionsConfirm());
    }
}
