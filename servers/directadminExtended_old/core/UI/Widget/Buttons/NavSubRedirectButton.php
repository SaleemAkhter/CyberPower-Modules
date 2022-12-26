<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;


class NavSubRedirectButton extends ButtonRedirect
{
    protected $class = ['lu-nav__link '];
    protected $htmlAttributes = [
        'href'        => 'javascript:;'
    ];
}