<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;

class Removefromreseller extends ButtonMassAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;
    protected $id    = 'removefromresellerButton';
    protected $name  = 'removefromresellerButton';
    protected $title = 'removefromresellerButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Removefromreseller());

        return parent::returnAjaxData();
    }
}
