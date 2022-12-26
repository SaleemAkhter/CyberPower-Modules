<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Providers\Ip;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Free extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'freeButton';
    protected $name  = 'freeButton';
    protected $title = 'freeButton';
    protected $icon  = '';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => '',
    ];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $provider=new Ip();
        $response=$provider->free();

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
    }
}
