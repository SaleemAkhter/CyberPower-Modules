<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals;

class Edit extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'edit';
    protected $name  = 'edit';
    protected $title = 'edit';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.user, dataRow.Path], null, true)';

        $this->setDisableByColumnValue('user', $this->getWhmcsParamByKey('username'));
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Edit());
        
        return parent::returnAjaxData();
    }
}
