<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\DropdawnButtonWrappers;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\DropdownItemInterface;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDropdown extends \ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonBase
{
    protected $id             = 'dropdownButton';
    protected $class          = ['lu-btn lu-btn--primary'];
    protected $icon           = 'lu-zmdi lu-zmdi-plus';
    protected $title          = 'dropdownButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];
    
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-dropdawn-btn-wrapper';    
    
    
}
