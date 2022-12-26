<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

class DeleteDatabase extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteDatabaseButton';
    protected $name  = 'deleteDatabaseButton';
    protected $title = 'deleteDatabaseButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\DeleteDatabase());
        
        return parent::returnAjaxData();
    }
}
