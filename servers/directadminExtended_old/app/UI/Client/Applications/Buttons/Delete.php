<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

class Delete extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';
    protected $icon  = 'lu-zmdi lu-zmdi-delete lu-dropdown__link-icon';
    protected $class = ['lu-dropdown__link lu-dropdown__link--danger'];

    public function initContent()
    {
        //$this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Delete());
        
        return parent::returnAjaxData();
    }
}
