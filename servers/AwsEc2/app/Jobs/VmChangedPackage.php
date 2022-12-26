<?php

namespace ModulesGarden\Servers\AwsEc2\App\Jobs;

use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\queue;

class VmChangedPackage extends VmCreated
{
    protected $newInstanceType = null;
    protected $newUserData = null;
    protected $volumeSize = null;
    protected $volumeType = null;

    public function handle($pid = null, $sid = null, $instanceId = null, $ipv4Count = 0, $tags = null, $securityGroupIds = null, $newInstanceType = null, $newUserData = null, $volumeSize = null, $volumeType = null)
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->instanceId = $instanceId;
        $this->securityGroupIds = $securityGroupIds;
        $this->newInstanceType = $newInstanceType;
        $this->newUserData = $newUserData;
        $this->volumeSize = $volumeSize;
        $this->volumeType = $volumeType;

        if ((int)$ipv4Count > 0)
        {
            $this->ipv4Count = (int)$ipv4Count;
        }

        if (is_array($tags))
        {
            $this->tags = $tags;
        }

        return $this->runTask();
    }

    public function runTaskActions()
    {
        $this->cancelPreviousTasks();

        $this->loadRemoteInstanceData();

        $this->updateInstanceType();
        $this->updateUserData();

        $this->updateVolumeData();

        $requiredIpCount = $this->ipv4Count;
        $remoteIpCount = $this->getRemoteIpCount();
        if ($requiredIpCount === $remoteIpCount)
        {
            return true;
        }

        if (!$this->isInstanceInProperState())
        {
            return $this->pospond();
        }

        if ((int)$requiredIpCount - (int)$remoteIpCount > 0)
        {
            $this->addNetworkInterfacesV4(((int)$requiredIpCount - (int)$remoteIpCount));
        }
        else if ((int)$remoteIpCount - (int)$requiredIpCount > 0)
        {
            $this->removeNetworkInterfaces4((int)$remoteIpCount - (int)$requiredIpCount);
        }

        return true;
    }

    public function updateVolumeData()
    {
        if ($this->volumeSize === null)
        {
            return null;
        }

        $productConfigRepo = new Repository();
        $productInfo = $productConfigRepo->getProductSettings($this->pid);

        $volumeInfo =  $this->apiConnection->describeVolumes([]);

        $requestParams = [
            'VolumeId' => $volumeInfo[0]['VolumeId'],
            'Size' => $this->volumeSize,
            'VolumeType' => $this->volumeType
        ];

        if($this->volumeType === 'io1' && !empty($productInfo['iops']))
        {
            $requestParams['Iops'] = $productInfo['iops'];
        }

        $this->apiConnection->modifyVolume($requestParams);
    }

    public function updateUserData()
    {
        if ($this->newUserData === null)
        {
            return null;
        }

        $this->apiConnection->modifyInstanceAttribute([
            'InstanceId' => $this->instanceId,
            'UserData' => [
                'Value' => $this->newUserData
            ]
        ]);
    }

    public function updateInstanceType()
    {
        if ($this->newInstanceType === null)
        {
            return null;
        }

        $this->apiConnection->modifyInstanceAttribute([
            'InstanceId' => $this->instanceId,
            'InstanceType' => [
                'Value' => $this->newInstanceType
            ],
        ]);
    }

    protected function removeNetworkInterfaces4($count = 0)
    {
        if (!is_int($count) || $count <= 0)
        {
            return false;
        }

        $interfaces = array_reverse($this->instanceData['NetworkInterfaces']);
        $deviceCount = count($interfaces);
        foreach ($interfaces as $networkInterface)
        {
            $publicIp = $networkInterface['Association']['PublicIp'];
            if(is_string($publicIp) && trim($publicIp) !== '' && $networkInterface['Association']['IpOwnerId'] !== 'amazon')
            {
                $ipDetails = $this->apiConnection->describeAddress($publicIp);

                $this->apiConnection->releaseAddress(['AllocationId' => $ipDetails['AllocationId']]);
            }

            //skipp default network interfaces
            if ($networkInterface['Attachment']['DeleteOnTermination'] === true)
            {
                continue;
            }

            if ($deviceCount > 1)
            {
                $this->apiConnection->detachNetworkInterface($networkInterface['Attachment']['AttachmentId']);

                $this->runInterfaceDetachedEvent($networkInterface['NetworkInterfaceId']);
            }

            $deviceCount --;
            $count--;
            if ($count === 0)
            {
                break;
            }
        }
    }

    protected function runInterfaceDetachedEvent($networkInterfaceId = null)
    {
        if (!is_string($networkInterfaceId) || trim($networkInterfaceId) === '')
        {
            return;
        }

        queue(
            \ModulesGarden\Servers\AwsEc2\App\Jobs\VmNetworkInterfaceDetached::class,
            [
                'pid' => $this->pid,
                'sid' => $this->sid,
                'instanceId' => $this->instanceData['InstanceId'],
                'networkInterfaceId' => $networkInterfaceId
            ],
            null,
            'Hosting',
            $this->sid,
            $this->instanceData['InstanceId']
        );
    }

    public function getRemoteIpCount()
    {
        $ipCount = 0;
        foreach ($this->instanceData['NetworkInterfaces'] as $networkInterface)
        {
            //skipp default network interfaces
            if (!$networkInterface['Association'] || $networkInterface['Association']['IpOwnerId'] === 'amazon')
            {
                continue;
            }

            $ipCount++;
        }

        return $ipCount;
    }

    public function isInstanceInProperState()
    {
        if (in_array($this->instanceData['State']['Name'], ['stopped']))
        {
            return true;
        }

        return false;
    }
}
