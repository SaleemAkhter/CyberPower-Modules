<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Modals;

class MarkRead extends ButtonMassAction implements ClientArea
{
    protected $id    = 'markReadManyButton';
    protected $name  = 'markReadManyButton';
    protected $title = 'markReadManyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-check';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\MarkRead());

        return parent::returnAjaxData();
    }
}
