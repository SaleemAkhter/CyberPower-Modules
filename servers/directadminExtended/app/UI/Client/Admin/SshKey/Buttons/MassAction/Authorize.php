<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Modals;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Providers;

class Authorize extends ButtonMassAction implements ClientArea
{
    protected $id    = 'authorizeButton';
    protected $name  = 'authorizeButton';
    protected $title = 'authorizeButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $provider=new Providers\SshKey();
        return $provider->authorize();
    }
}
