<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

class Settings extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'settingsButton';
    protected $name  = 'settingsButton';
    protected $title = 'settingsButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-settings';
    
    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Settings());
        return parent::returnAjaxData();
    }
}
