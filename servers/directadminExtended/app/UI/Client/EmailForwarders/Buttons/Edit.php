<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Modals;

class Edit extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'editButton';
    protected $name  = 'editButton';
    protected $title = 'editButton';

    public function initContent()
    {
        $this->addHtmlAttribute('@click', 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.id,dataRow.email], null, true)');
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Edit());
        return parent::returnAjaxData();
    }
}
