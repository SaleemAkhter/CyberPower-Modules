<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

class Install extends ButtonMassAction implements ClientArea
{
    protected $id    = 'installButton';
    protected $name  = 'installButton';
    protected $title = 'installButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('installed', "yes");
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Install());

        return parent::returnAjaxData();
    }
}
