<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Modals;

class Unsuspend extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'unsuspendButton';
    protected $name  = 'unsuspendButton';
    protected $title = 'unsuspendButton';
    protected $icon = '';
    protected $class=['lu-btn lu-btn--sm lu-btn lu-btn--link lu-btn--icon lu-btn--success'];
    protected $isSuspended=false;

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name], null, true)';
        $this->setHideByColumnValue('suspended', "No");
    }

    public function returnAjaxData()
    {

        $this->setModal(new Modals\Unsuspend());

        return parent::returnAjaxData();
    }


}
