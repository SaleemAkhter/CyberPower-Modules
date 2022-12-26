<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;

class CreateBackup extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'backupButton';
    protected $name  = 'backupButton';
    protected $title = 'backupButton';
    protected $icon  = 'lu-zmdi lu-zmdi-time-restore-setting lu-dropdown__link-icon';
    protected $class = ['lu-dropdown__link'];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\CreateBackup());
        
        return parent::returnAjaxData();
    }
}
