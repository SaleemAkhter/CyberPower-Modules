<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\AddIconModalButton;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

class DropdownDataTableButton extends AddIconModalButton implements ClientArea
{
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
