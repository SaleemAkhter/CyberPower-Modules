<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;

class Delete extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteMassButton';
    protected $name  = 'deleteMassButton';
    protected $title = 'deleteMassButton';

    public function initContent()
    {
        $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Modals\MassAction\Delete());

        return parent::returnAjaxData();
    }
}
