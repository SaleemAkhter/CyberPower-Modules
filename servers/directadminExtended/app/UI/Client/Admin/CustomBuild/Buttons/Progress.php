<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class Progress extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'progressButton';
    protected $name  = 'progressButton';
    protected $title = 'progressButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-info-outline';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Progress());

        return parent::returnAjaxData();
    }
}
