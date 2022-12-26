<?php


namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\Core\Traits\Lang;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\Traits\AdminAreaServicePageHelper;

class ReinstallProvider extends BaseDataProvider
{

    public function read()
    {

    }

    public function update()
    {

        $response = (new InstanceFactory())->fromWhmcsParams()->reinstall();
        if($response->instance->default_password){
            $hosting              = $this->getWhmcsParamByKey('model');
            $hosting->password = encrypt((string)$response->instance->default_password);
            $hosting->save();
        }
        return (new RawDataJsonResponse())->setMessageAndTranslate('serviceReinstalled')
            ->setCallBackFunction('awsReloadInstanceDetails');
    }
}
