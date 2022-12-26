<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\FileEditor\Modals;

class Auth extends ButtonCreate implements ClientArea
{
    protected $id    = 'authButton';
    protected $name  = 'authButton';
    protected $title = 'authButton';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Auth());
        $this->setCallBackFunction('loadFileDataToAuthModal');
        return parent::returnAjaxData();
    }
}
