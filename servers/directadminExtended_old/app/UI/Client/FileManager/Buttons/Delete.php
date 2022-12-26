<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;

class Delete extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButton';
    protected $class = ['lu-dropdown__link lu-dropdown__link--danger'];
    protected $icon  = 'lu-dropdown__link-icon lu-zmdi lu-zmdi-delete';

    public function initContent()
    {

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.truepath], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Delete());

        return parent::returnAjaxData();
    }

//    public function switchToRemoveBtn()
//    {
//        $this->replaceClasses(['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain']);
//        $this->setIcon('lu-btn__icon lu-zmdi lu-zmdi-delete');
//
//        return $this;
//    }
}
