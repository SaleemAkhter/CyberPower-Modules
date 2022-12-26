<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MxRecords\Modals;

class Delete extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteRecord';
    protected $name  = 'deleteRecord';
    protected $title = 'deleteRecord';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', dataRow.name, null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Delete());
        return parent::returnAjaxData();
    }
}
