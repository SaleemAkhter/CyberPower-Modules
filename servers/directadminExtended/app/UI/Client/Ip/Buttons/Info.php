<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class Info extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'infoButton';
    protected $name  = 'infoButton';
    protected $title = 'infoButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-info-outline';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Info());
        
        return parent::returnAjaxData();
    }
}
