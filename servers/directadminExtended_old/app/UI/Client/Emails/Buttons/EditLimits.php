<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Modals;

class EditLimits extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'edit';
    protected $name  = 'edit';
    protected $title = 'edit';

    public function initContent()
    {
        $this->addHtmlAttribute('@click', 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.id,dataRow.usage,dataRow.limit], null, true)');
        $this->setDisableByColumnValue('main', true);
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\EditLimits());
        return parent::returnAjaxData();
    }
}
