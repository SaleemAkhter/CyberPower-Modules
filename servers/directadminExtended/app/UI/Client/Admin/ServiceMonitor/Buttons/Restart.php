<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Providers\ServiceMonitor;

class Restart extends ButtonDataTableModalAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\HideButtonByColumnValue;

    protected $id    = 'restartButton';
    protected $name  = 'restartButton';
    protected $title = 'restartButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setHideByColumnValue('restart', "No");

    }

    public function returnAjaxData()
    {
        $provider=new ServiceMonitor();
        $response=$provider->restart();

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('serviceRetartSuccessfully');
    }
}
