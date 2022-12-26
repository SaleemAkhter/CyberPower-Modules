<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Modals;

class SuspendUnsuspend extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'suspendButton';
    protected $name  = 'suspendButton';
    protected $title = 'suspendButton';
    protected $icon = 'lu-zmdi lu-zmdi-power';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name], null, true)';
        $this->setDisableByColumnValue('user', $this->getWhmcsParamByKey('username'));
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\SuspendUnsuspend());

        return parent::returnAjaxData();
    }
}