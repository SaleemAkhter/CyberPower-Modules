<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Providers\Ip;

class Setglobal extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'setglobalButton';
    protected $name  = 'setglobalButton';
    protected $title = 'setglobalButton';
    protected $icon  = '';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => '',
    ];
    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('global', "No");
    }

    public function returnAjaxData()
    {
        $provider=new Ip();
        $response=$provider->setglobal();

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('ipGlobalSetDone');
    }
}
