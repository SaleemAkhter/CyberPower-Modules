<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Providers\ServiceMonitor;

class Stop extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'stopButton';
    protected $name  = 'stopButton';
    protected $title = 'stopButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('canstop', "no");
    }

    public function returnAjaxData()
    {
        $provider=new ServiceMonitor();
        $response=$provider->stop();

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('serviceStoppedSuccessfully');
    }
}
