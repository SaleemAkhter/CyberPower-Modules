<?php


namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Providers;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\Core\Traits\Lang;
use ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\VultrVps\Packages\WhmcsService\Traits\AdminAreaServicePageHelper;

class ServiceActions extends BaseDataProvider
{
    use AdminAreaServicePageHelper;
    use Lang;

    public function read()
    {

    }

    public function start()
    {
        try
        {
            (new InstanceFactory())->fromWhmcsParams()->start();
            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceStarted')
                ->setCallBackFunction('awsReloadInstanceDetails');
        }
        catch (\Exception $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
        }
    }

    public function stop()
    {
        try
        {
            (new InstanceFactory())->fromWhmcsParams()->halt();

            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceStopped')
                ->setCallBackFunction('awsReloadInstanceDetails');
        }
        catch (\Exception $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
        }
    }

    public function reboot()
    {
        try
        {
            (new InstanceFactory())->fromWhmcsParams()->reboot();
            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceRebooted')
                ->setCallBackFunction($this->callBackFunction);
        }
        catch (\Exception $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getMessage())
                ->setCallBackFunction('awsReloadInstanceDetails')->setRefreshTargetIds((array)$this->refreshActionIds);
        }
    }

    public function update()
    {
        //do nothing
    }
}
