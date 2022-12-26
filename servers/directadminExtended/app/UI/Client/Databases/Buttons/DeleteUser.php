<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

class DeleteUser extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', dataRow.name, null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\DeleteUser());
        
        return parent::returnAjaxData();
    }
}
