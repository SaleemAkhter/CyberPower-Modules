<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class ChangePassword extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'changePasswordButton';
    protected $name  = 'changePasswordButton';
    protected $title = 'changePasswordButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-key';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name, dataRow.user], null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\ChangePassword());

        return parent::returnAjaxData();
    }
}
