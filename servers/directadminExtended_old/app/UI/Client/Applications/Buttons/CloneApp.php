<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;

class CloneApp extends ButtonDropdownItem implements ClientArea
{
    protected $id    = 'cloneAppButton';
    protected $name  = 'cloneAppButton';
    protected $title = 'cloneAppButton';
    protected $icon  = 'lu-zmdi lu-zmdi-plus-circle-o-duplicate lu-dropdown__link-icon';
    protected $class = ['lu-dropdown__link'];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\CloneApp());

        return parent::returnAjaxData();
    }
}