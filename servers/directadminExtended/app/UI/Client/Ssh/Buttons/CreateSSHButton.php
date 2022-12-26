<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;


class CreateSSHButton extends ButtonCreate implements ClientArea
{
    protected $id    = 'addButton';
    protected $name  = 'addButton';
    protected $title = 'addButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\CreateSSHModal());

        return parent::returnAjaxData();
    }
}