<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Modals;

class Restore extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'restoreButton';
    protected $name  = 'restoreButton';
    protected $title = 'restoreButton';
    protected $icon  = 'lu-zmdi lu-zmdi-refresh';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Restore());
        
        return parent::returnAjaxData();
    }
}
