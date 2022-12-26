<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

class DeleteBackup extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteBackupButton';
    protected $name  = 'deleteBackupButton';
    protected $title = 'deleteBackupButton';
    protected $icon  = 'lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\DeleteBackup());
        
        return parent::returnAjaxData();
    }
}
