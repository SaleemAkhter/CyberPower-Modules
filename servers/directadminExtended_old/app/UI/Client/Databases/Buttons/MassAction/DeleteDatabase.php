<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

class DeleteDatabase extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteDatabaseButton';
    protected $name  = 'deleteDatabaseButton';
    protected $title = 'deleteDatabaseButton';
    protected $icon  = 'lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\DeleteDatabase());
        
        return parent::returnAjaxData();
    }
}
