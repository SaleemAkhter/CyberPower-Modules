<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Providers;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\Helpers\WindowsPassword;
use ModulesGarden\Servers\AwsEc2\App\Models\SSHKey\SSHKeysRepository;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Traits\AdminAreaServicePageHelper;

class ServiceActions extends BaseDataProvider implements ClientArea, AdminArea
{
    use AdminAreaServicePageHelper;
    use Lang;

    public function read()
    {
        $serviceId = $this->getServiceIdForAAServicePage();
        $sshRepo = new SSHKeysRepository();
        $this->data['keys'] = $sshRepo->get($serviceId);
        $keys = $sshRepo->get($serviceId);
        $this->data['private_key'] = $keys['private_key'];
        $this->data['public_key'] = $keys['public_key'];

        if ($this->getRequestValue('download') == 'file')
        {
            return $this->download();
        }
    }

    public function start()
    {
        try
        {
            $serviceId = $this->getServiceIdForAAServicePage();
            $service = new Service($serviceId);

            $productId = $service->getProductId();

            $apiConnection = new ClientWrapper($productId, $serviceId);

            $instanceId = $service->getServiceInstanceId();

            $result = $apiConnection->startInstances(['InstanceIds' => [$instanceId]]);

            $instances = $result->get('StartingInstances');
            $instance = $instances[0];

            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceStarted')
                ->setCallBackFunction('awsReloadInstanceDetails');
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getAwsErrorMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
        }
    }

    public function stop()
    {
        try
        {
            $serviceId = $this->getServiceIdForAAServicePage();
            $service = new Service($serviceId);

            $productId = $service->getProductId();

            $apiConnection = new ClientWrapper($productId, $serviceId);

            $instanceId = $service->getServiceInstanceId();

            $result = $apiConnection->stopInstances(['InstanceIds' => [$instanceId]]);

            $instances = $result->get('StoppingInstances');
            $instance = $instances[0];

            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceStopped')
                ->setCallBackFunction('awsReloadInstanceDetails');
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getAwsErrorMessage())
                ->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
        }
    }

    public function reboot()
    {
        try
        {
            $serviceId = $this->getServiceIdForAAServicePage();
            $service = new Service($serviceId);

            $productId = $service->getProductId();

            $apiConnection = new ClientWrapper($productId, $serviceId);

            $instanceId = $service->getServiceInstanceId();

            $result = $apiConnection->rebootInstances(['InstanceIds' => [$instanceId]]);

            return (new RawDataJsonResponse())->setMessageAndTranslate('ServiceRebooted')
                ->setCallBackFunction($this->callBackFunction);
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessage($exc->getAwsErrorMessage())
                ->setCallBackFunction('awsReloadInstanceDetails')->setRefreshTargetIds($this->refreshActionIds);
        }
    }

    public function decode()
    {
        $serviceId = $this->getServiceIdForAAServicePage();
        $service = new Service($serviceId);

        $customFields = $service->getCustomFieldsValues();
        $encodedPassword = $customFields['windowsPassword']['value'];

        $passwordHelper = new WindowsPassword();
        $decoded = $passwordHelper->decode($encodedPassword, $this->formData['privateKey']);
        if ($decoded === false)
        {
            return (new RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('unableToDecodePassword')
                ->setCallBackFunction('awsShowWindowsPassword');
        }

        $this->loadLang();

        return (new RawDataJsonResponse())->setData([
            'password' => base64_encode($decoded),
            'passwordLabel' => $this->lang->translate('windowsPasswordDecode', 'decodeForm', 'password', 'passwordLabel')
        ])
            ->setCallBackFunction('awsShowWindowsPassword')
            ->setPreventCloseModal()
            ->setMessageAndTranslate('passwordDecodedSuccessfully');
    }

    public function update()
    {
        //do nothing
    }

    public function download(){
        $sshRepo = new SSHKeysRepository();
        $serviceId = $this->getServiceIdForAAServicePage();
        $content = $sshRepo->get($serviceId)['private_key'];
        $filename = '"' . $this->getWhmcsParamByKey('domain') . '-private.key"';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . strlen($content));
        $sshRepo->clearPrivateKey($serviceId);
        echo $content;
        exit;
    }

    public function inject()
    {
        try {
            $publicKey = $this->formData['publicKey'];
            if (!$publicKey)
                return (new RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('emptyPublicKeyError');
            if (!$this->validatePublicSshKey($publicKey))
                return (new RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('publicKeyValidationError');

            $serviceId = $this->getServiceIdForAAServicePage();
            $service = new Service($serviceId);
            $instanceId = $service->getServiceInstanceId();
            $productId = $service->getProductId();

            $apiConnection = new ClientWrapper($productId, $serviceId);

            $instance = $apiConnection->describeInstances(['InstanceIds' => [$instanceId]]);
            $reservations = $instance->get('Reservations');
            $instanceData = $reservations[0]['Instances'][0];
            $status = $instanceData["State"]['Name'];

            if ($status != 'stopped')
                return (new RawDataJsonResponse())->setStatusError()->setMessageAndTranslate('instanceNotStoppedError');

            $username = $this->formData['username'];
            $params = [
                'InstanceId' => $instanceId,
                'UserData' => [
                    'Value' => $this->getUserData($publicKey, $username)
                ]
            ];

            $apiConnection->modifyInstanceAttribute($params);
            return (new RawDataJsonResponse())
                ->setStatusSuccess()
                ->setRefreshTargetIds(['statusWidget'])
                ->setCallBackFunction('awsReloadInstanceDetails')
                ->setMessageAndTranslate('keyInjectionSuccess');
        } catch (\Exception $e) {
            return (new RawDataJsonResponse())->setStatusError()->setMessage('Something went wrong.');
        }

    }

    protected function getUserData($publicKey, $username)
    {
        return
"Content-Type: multipart/mixed; boundary=\"//\"
MIME-Version: 1.0

--//
Content-Type: text/cloud-config; charset=\"us-ascii\"
MIME-Version: 1.0
Content-Transfer-Encoding: 7bit
Content-Disposition: attachment; filename=\"cloud-config.txt\"

#cloud-config
cloud_final_modules:
- [users-groups, once]
users:
  - name: " . $username . "
    ssh-authorized-keys: 
    - " . $publicKey;
    }

    protected function validatePublicSshKey($publicKey)
    {
        if (!preg_match('#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#',
            $publicKey)) {
            return false;
        } else {
            return true;
        }
    }

}
