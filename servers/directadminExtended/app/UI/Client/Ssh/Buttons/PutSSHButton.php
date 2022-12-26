<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;


class PutSSHButton extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'putButton';
    protected $name  = 'putButton';
    protected $title = 'putButton';
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-key';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\PutSSHModal());

        return parent::returnAjaxData();
    }
}