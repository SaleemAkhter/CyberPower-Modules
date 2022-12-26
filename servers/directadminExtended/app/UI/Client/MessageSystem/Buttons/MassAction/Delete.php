<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Modals;

class Delete extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deleteManyButton';
    protected $name  = 'deleteManyButton';
    protected $title = 'deleteManyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-check';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\Delete());

        return parent::returnAjaxData();
    }
}
