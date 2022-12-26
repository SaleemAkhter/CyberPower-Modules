<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals;

class Suspend extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'toggleSuspendButton';
    protected $name  = 'toggleSuspendButton';
    protected $title = 'toggleSuspendButton';
    protected $icon = 'lu-zmdi lu-zmdi-power';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setDisableByColumnValue('user', $this->getWhmcsParamByKey('username'));
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Suspend());

        return parent::returnAjaxData();
    }
}