<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Modals\CreateCustomFieldsConfirm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateCustomFieldsBaseModalButton extends ButtonCreate implements AdminArea {

    protected $id             = 'createCustomFieldsBaseModalButton'; // atrybut id w tag-u
    protected $name           = 'createCustomFieldsBaseModalButton'; // atrybut name w tagu
    protected $title          = 'createCustomFieldsBaseModalButton';
    protected $class          = ['lu-btn lu-btn--success'];
    protected $htmlAttributes = [
        'href' => 'javascript:;',
    ];

    public function initContent() {
        $this->initLoadModalAction(new CreateCustomFieldsConfirm());
    }

}
