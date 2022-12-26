<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownDataTableButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

class Permissions extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'permissionsButton';
    protected $name  = 'permissionsButton';
    protected $title = 'permissionsButton';
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-key';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Permissions());

        return parent::returnAjaxData();
    }
}
