<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ProtectedDirectories\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class Delete extends ButtonDataTableModalAction  implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setDisableByColumnValue('user', $this->getWhmcsParamByKey('username'));
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Delete());

        return parent::returnAjaxData();
    }
}
