<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Buttons\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

class DeleteSub extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteSubManyButton';
    protected $name  = 'deleteSubManyButton';
    protected $title = 'deleteSubManyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\DeleteSub());

        return parent::returnAjaxData();
    }
}