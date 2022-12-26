<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Modals;

class Suspend extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'suspendButton';
    protected $name  = 'suspendButton';
    protected $title = 'suspendButton';
    protected $icon = '';
    protected $isSuspended=false;
    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name], null, true)';
        $this->setHideByColumnValue('suspended', "Yes");
    }

    public function returnAjaxData()
    {

        $this->setModal(new Modals\Suspend());

        return parent::returnAjaxData();
    }


}
