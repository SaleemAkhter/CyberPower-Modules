<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;

class Upload extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'uploadButton';
    protected $name  = 'uploadButton';
    protected $title = 'uploadButton';
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-upload';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Upload());

        return parent::returnAjaxData();
    }
}
