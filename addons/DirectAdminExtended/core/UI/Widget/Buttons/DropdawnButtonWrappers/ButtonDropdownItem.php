<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\DropdownItemInterface;

/**
 * A button in dropdown buttons list
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDropdownItem extends \ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonModal
{
    protected $id             = 'duttonDropdownItem';
    protected $class          = ['lu-dropdown__link'];
    protected $icon           = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-edit';
    protected $title          = 'dropdownButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;'
    ];
}
