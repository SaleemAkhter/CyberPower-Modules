<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

class EditPrivileges extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'editPrivilegesButton';
    protected $name  = 'editPrivilegesButton';
    protected $title = 'editPrivilegesButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name, dataRow.user], null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\EditPrivileges());

        return parent::returnAjaxData();
    }
}
