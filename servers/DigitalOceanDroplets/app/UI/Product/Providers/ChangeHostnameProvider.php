<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Providers;

use Exception;


use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ChangeHostnameProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->whmcsParams['serviceid'];
        $this->data['hostname'] = $this->whmcsParams['domain'];
    }

    public function create()
    {
    }

    public function delete()
    {
    }

    public function update()
    {
        if($this->whmcsParams['domain'] == $this->formData['hostname']){
            return (new HtmlDataJsonResponse())
                ->setStatusError()
                ->setMessageAndTranslate('diffrentHostname');
        }


        $serviceManager = new ServiceManager($this->whmcsParams);

        $vm = $serviceManager->getVM();

        $response =  $vm->rename($this->formData['hostname']);

        if(is_null($response)){
            return (new HtmlDataJsonResponse())
                ->setStatusError()
                ->setMessageAndTranslate('changeHostnameError');
        }

        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setMessageAndTranslate('changeHostnameSuccess');

    }

}
