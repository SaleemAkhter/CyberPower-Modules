<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\HideButtonByColumnValue;

class DropdownDataTableButton extends AddIconModalButton implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;
    use HideButtonByColumnValue;

    protected $id    = 'dropdownDataTableButton';
    protected $name  = 'dropdownDataTableButton';
    protected $title = 'dropdownDataTableButton';
    protected $icon  = 'lu-btn--icon lu-zmdi lu-zmdi-plus';
    protected $showButton = '';

    protected $htmlAttributes = [
        'href'        => 'javascript:;',
    ];

    public function showWhere($where = '')
    {
        $this->showButton = 'v-show="' . $where . '"';

        return $this;
    }

    public function getShowButton()
    {
        return $this->showButton;
    }
}