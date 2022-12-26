<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Modals;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\HideButtonByColumnValue;

class Activate extends ButtonMassAction implements ClientArea
{
    use HideButtonByColumnValue;
    protected $id    = 'activateButton';
    protected $name  = 'activateButton';
    protected $title = 'activateButton';
    protected $icon  = '';
    protected $isActive=false;

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('showactivatebtn', "no");
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Activate());

        return parent::returnAjaxData();
    }
}
