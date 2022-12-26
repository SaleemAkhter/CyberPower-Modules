<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

class Deactivate extends ButtonMassAction implements ClientArea
{
    protected $id    = 'deactivateButton';
    protected $name  = 'deactivateButton';
    protected $title = 'deactivateButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('active', "no");
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Deactivate());

        return parent::returnAjaxData();
    }
}
