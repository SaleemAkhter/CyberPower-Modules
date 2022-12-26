<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;

class UnInstall extends ButtonMassAction implements ClientArea
{
    protected $id    = 'uninstallButton';
    protected $name  = 'uninstallButton';
    protected $title = 'uninstallButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('installed', "no");
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Uninstall());

        return parent::returnAjaxData();
    }
}
