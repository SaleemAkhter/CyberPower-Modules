<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseButton extends BaseContainer
{
    protected $id             = 'baseButton';
    protected $name           = 'baseButton';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'baseButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'onclick'     => '',
        'data-toggle' => 'tooltip'
    ];
}
