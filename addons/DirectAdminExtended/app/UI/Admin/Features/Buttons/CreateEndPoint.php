<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

class CreateEndPoint extends ButtonCreate implements AdminArea
{
    protected $id    = 'createEndPoint';
    protected $name  = 'createEndPoint';
    protected $title = 'createEndPoint';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\CreateEndPoint());

        return parent::returnAjaxData();
    }
}
