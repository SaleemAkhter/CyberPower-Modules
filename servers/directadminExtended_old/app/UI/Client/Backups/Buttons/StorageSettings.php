<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;

class StorageSettings extends DropdownDataTableButton implements ClientArea
{
    protected $id    = 'storageSettingsButton';
    protected $name  = 'storageSettingsButton';
    protected $title = 'storageSettingsButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-folder';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\StorageSettings());
        
        return parent::returnAjaxData();
    }
}
