<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Buttons\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals;

class Suspend extends ButtonMassAction implements ClientArea
{
    protected $id    = 'suspendButton';
    protected $name  = 'suspendButton';
    protected $title = 'suspendButton';
    protected $icon  = 'lu-zmdi lu-zmdi-power';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\Suspend());

        return parent::returnAjaxData();
    }

}