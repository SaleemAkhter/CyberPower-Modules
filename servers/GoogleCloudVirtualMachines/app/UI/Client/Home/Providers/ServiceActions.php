<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Providers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\AwsIntegration\Helpers\WindowsPassword;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits\Lang;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\WhmcsService\Traits\AdminAreaServicePageHelper;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\GcpPasswordReset\GcpPasswordReset;

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
            $instace = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $compute->instances->start($poject , $instace->getZone(), $instace->getId());
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
            $instace = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $compute->instances->stop($poject , $instace->getZone(), $instace->getId() );
            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceStopped')
                ->setCallBackFunction('awsReloadInstanceDetails');
        }
        catch (\Exception $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
        }
    }

    public function reset()
    {
        try
        {
            $instace = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $compute->instances->reset($poject , $instace->getZone(), $instace->getId() );
            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceReset')
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

    public function resetPassword()
    {
        try
        {
            $instance = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $project = (new ProjectFactory())->fromParams();
            $email = $this->getWhmcsParamByKey('clientsdetails')['email'];
            $productId = $this->getWhmcsParamByKey('serviceid');

            $passwordRester = new GcpPasswordReset($instance, $compute, $project, $email);

            $newPassword = $passwordRester->getNewPassword();
            $passwordRester->setNewPasswordForWhmcsOrder($productId, $newPassword);

            return (new RawDataJsonResponse())
                ->setMessageAndTranslate('PasswordReseted')
                ->setCallBackFunction('replaceTextInputWithPassword')
                ->setData($newPassword)
                ->setPreventCloseModal();
        }
        catch (\Exception $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds((array)$this->refreshActionIds);
        }
    }
}
