<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

class DeleteSub extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'delete';
    protected $name  = 'delete';
    protected $title = 'delete';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', dataRow.type, null, true)';
        $this->switchToRemoveBtn();
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\DeleteSub());
        return parent::returnAjaxData();
    }
}
