<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PhpConfig\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PhpConfig\Providers;

class EnableOpenbasedir extends ButtonMassAction implements ClientArea
{
    protected $id    = 'enableOpenbasedirButton';
    protected $name  = 'enableOpenbasedirButton';
    protected $title = 'enableOpenbasedirButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $provider=new Providers\PhpConfig();
        return $provider->enableOpenbasedir();
    }
}
