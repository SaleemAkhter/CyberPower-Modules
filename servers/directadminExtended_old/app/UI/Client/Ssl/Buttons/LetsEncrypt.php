<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;

class LetsEncrypt extends ButtonCreate implements ClientArea
{
    protected $id    = 'letsEncrypt';
    protected $name  = 'letsEncrypt';
    protected $title = 'letsEncrypt';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-key';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\LetsEncrypt());
        
        return parent::returnAjaxData();
    }
}
