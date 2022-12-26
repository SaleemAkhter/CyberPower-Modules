<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;

class EditSSHButton extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'editButton';
    protected $name  = 'editButton';
    protected $title = 'editButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.user, dataRow.Path], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\EditSSHModal());

        return parent::returnAjaxData();
    }
}