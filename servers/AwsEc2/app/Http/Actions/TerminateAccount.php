<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Events\VmDeleted;
use ModulesGarden\Servers\AwsEc2\App\Helpers\ProvisioningConstants;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\queue;

/**
 * Class TerminateAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class TerminateAccount extends CreateAccount
{
    public function execute($params = null)
    {
        try
        {
            $this->loadProductConfig($params);

            $this->loadApiConnection();

            $instanceDetails = $this->getInstanceDetails();

            $this->deleteKey($instanceDetails['KeyName']);

            $this->deleteNetworkInterfaces($instanceDetails);

            $params = $this->prepareRequestParams();

            $result = $this->apiConnection->terminateInstances($params);

            $instanceName = '';
            foreach ($instanceDetails['Tags'] as $tag) {
                if ($tag['Key'] == 'Name')
                    $instanceName = $tag['Value'];
            }

            $instanceDetails['securityGroupIdToDelete'] = $this->apiConnection->getSecurityGroup($instanceName)['GroupId'];
            $terminatedInstances = $result->get('StoppingInstances');

            $this->runInstanceDeleteEvent($instanceDetails);

            $this->updateCustomField(ProvisioningConstants::INSTANCE_ID, '');
            $this->updateCustomField(ProvisioningConstants::INSTANCE_TAGS, '');

            return 'success';
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            return $exc->getAwsErrorMessage();
        }
        catch (\Exception $exc)
        {
            return $exc->getMessage();
        }
    }

    protected function runInstanceDeleteEvent($instanceDetails = null)
    {
        queue(
            \ModulesGarden\Servers\AwsEc2\App\Jobs\VmDeleted::class,
            [
                'pid' => $this->pid,
                'sid' => $this->sid,
                'instanceData' => $instanceDetails
            ],
            null,
            'Hosting',
            $this->sid,
            $instanceDetails['InstanceId']
        );
    }

    protected function deleteNetworkInterfaces($instanceDetails)
    {
        foreach ($instanceDetails['NetworkInterfaces'] as $networkInterface)
        {
            $publicIp = $networkInterface['Association']['PublicIp'];

            if ($networkInterface['Association']['IpOwnerId'] === 'amazon')
            {
                continue;
            }
            else if(is_string($publicIp) && trim($publicIp) !== '')
            {
                $ipDetails = $this->apiConnection->describeAddress($publicIp);

                $this->apiConnection->releaseAddress(['AllocationId' => $ipDetails['AllocationId']]);
            }

            if ($networkInterface['Attachment']['DeleteOnTermination'] !== true)
            {
                $this->apiConnection->detachNetworkInterface($networkInterface['Attachment']['AttachmentId']);
            }
        }
    }

    protected function getInstanceDetails()
    {
        $instancesData = $this->apiConnection->describeInstances($this->prepareRequestParams());
        $reservations = $instancesData->get('Reservations');

        $instanceDetails = $reservations[0]['Instances'][0];

        return $instanceDetails;
    }

    protected function prepareRequestParams()
    {
        $params = [
            'InstanceIds' => $this->getInstanceIds()
        ];

        return $params;
    }
}
