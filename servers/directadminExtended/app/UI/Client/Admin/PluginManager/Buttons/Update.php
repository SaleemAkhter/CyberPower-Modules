<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

class Update extends ButtonMassAction implements ClientArea
{
    protected $id    = 'updateButton';
    protected $name  = 'updateButton';
    protected $title = 'updateButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('canupdate', "No");
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Update());

        return parent::returnAjaxData();
    }
}
