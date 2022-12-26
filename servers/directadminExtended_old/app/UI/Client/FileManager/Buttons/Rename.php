<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

class Rename extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'renameButton';
    protected $name  = 'renameButton';
    protected $title = 'renameButton';
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Rename());

        return parent::returnAjaxData();
    }
}
