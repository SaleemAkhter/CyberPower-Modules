<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

class AddUser extends ButtonCreate implements ClientArea
{
    protected $id    = 'addUserButton';
    protected $name  = 'addUserButton';
    protected $title = 'addUserButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\AddUser());
        
        return parent::returnAjaxData();
    }
}
