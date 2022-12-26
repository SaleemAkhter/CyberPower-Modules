<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

class RestoreBackup extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'restoreBackupButton';
    protected $name  = 'restoreBackupButton';
    protected $title = 'restoreBackupButton';
    protected $icon  = 'lu-zmdi lu-zmdi-time-restore';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\RestoreBackup());
        
        return parent::returnAjaxData();
    }
}
