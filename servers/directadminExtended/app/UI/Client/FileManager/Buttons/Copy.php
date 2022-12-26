<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;

class Copy extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'copyButton';
    protected $name  = 'copyButton';
    protected $title = 'copyButton';
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-copy';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', dataRow.truepath, null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Copy());

        return parent::returnAjaxData();
    }
}
