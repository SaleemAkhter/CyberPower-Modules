<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;


class Settings extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'settingsButton';
    protected $name  = 'settingsButton';
    protected $title = 'settingsButton';
    protected $icon = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-settings';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\SettingsModal());
        
        return parent::returnAjaxData();
    }
}