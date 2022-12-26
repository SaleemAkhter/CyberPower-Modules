<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

/**
 * Description of AddIconModalButton
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class AddIconModalButton extends BaseModalButton
{
    protected $id             = 'addIconModalButton';
    protected $class          = ['lu-btn lu-btn--primary'];
    protected $icon           = 'lu-btn--icon lu-zmdi lu-zmdi-plus';
    protected $title          = 'addIconModalButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;'
    ];
}
