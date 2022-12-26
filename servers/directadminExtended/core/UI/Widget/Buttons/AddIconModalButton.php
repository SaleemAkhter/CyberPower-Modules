<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;


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