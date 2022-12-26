<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\DropdawnButtonWrappers;

/**
 * A button in dropdawn buttons list
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDropdownItem extends \ModulesGarden\Servers\VultrVps\Core\UI\Widget\Buttons\ButtonModal
{
    protected $id             = 'duttonDropdownItem';
    protected $class          = ['lu-dropdown__link'];
    protected $icon           = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-edit';
    protected $title          = 'dropdownButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;'
    ];
}
