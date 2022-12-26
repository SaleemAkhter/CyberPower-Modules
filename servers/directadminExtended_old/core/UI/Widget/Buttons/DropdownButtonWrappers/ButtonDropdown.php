<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\DropdownItemInterface;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonDropdown extends \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonBase
{
    protected $id             = 'dropdownButton';
    protected $icon           = 'lu-zmdi lu-zmdi-plus';
    protected $title          = 'dropdownButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];
    
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-dropdown-btn-wrapper';

    public function isVueRegistrationAllowed()
    {
        if ($this->getRequestValue('ajax', null) == 1)
        {
            return false;
        }

        return true;
    }
}
