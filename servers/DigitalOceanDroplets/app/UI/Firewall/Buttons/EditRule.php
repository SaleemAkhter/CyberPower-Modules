<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Modals\EditRuleModal;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;

class EditRule extends BaseModalButton implements ClientArea, AdminArea
{
    protected $id             = 'editRuleButton';
    protected $title          = 'editRuleButton';
    protected $class          = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center'];
    protected $icon           ='lu-zmdi lu-zmdi-edit';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip'
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new EditRuleModal());
    }

}