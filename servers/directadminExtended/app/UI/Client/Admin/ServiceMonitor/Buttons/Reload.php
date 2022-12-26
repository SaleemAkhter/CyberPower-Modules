<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Providers\ServiceMonitor;

class Reload extends ButtonDataTableModalAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\HideButtonByColumnValue;
    protected $id    = 'reloadButton';
    protected $name  = 'reloadButton';
    protected $title = 'reloadButton';
    protected $icon  = '';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => '',
    ];
    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('canreload', "true");
    }

    public function returnAjaxData()
    {
        $provider=new ServiceMonitor();
        $response=$provider->reload();

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('serviceReloadedSuccessfully');
    }
}
