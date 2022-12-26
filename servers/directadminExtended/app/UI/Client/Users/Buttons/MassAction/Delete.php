<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Modals;

class Delete extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';
    protected $icon  = 'lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\Delete());
        
        return parent::returnAjaxData();
    }
}
