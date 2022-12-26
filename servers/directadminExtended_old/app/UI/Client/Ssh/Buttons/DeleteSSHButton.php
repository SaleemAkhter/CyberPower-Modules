<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;

class DeleteSSHButton extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.user, dataRow.Path], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\DeleteSSHModal());

        return parent::returnAjaxData();
    }
}