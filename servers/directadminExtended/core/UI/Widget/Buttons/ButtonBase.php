<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonBase extends BaseContainer
{
    protected $id             = 'ButtonBase';
    protected $name           = 'ButtonBase';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'lu-zmdi lu-zmdi-plus';
    protected $title          = 'ButtonBase';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip'
    ];
}
