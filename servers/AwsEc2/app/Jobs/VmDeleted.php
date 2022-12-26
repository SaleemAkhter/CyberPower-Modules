<?php

namespace ModulesGarden\Servers\AwsEc2\App\Jobs;

use Aws\Exception\AwsException;
use ModulesGarden\Servers\AwsEc2\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\AwsEc2\App\Models\NetworkInterface;

class VmDeleted extends VmCreated
{
    protected $instanceData = null;

    public function handle($pid = null, $sid = null, $instanceData = null, $ipv4Count = 0, $tags = null, $securityGroupIds = null)
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->instanceData = $instanceData;

        return $this->runTask();
    }

    public function runTaskActions()
    {
        if(!$this->isInstanceInProperState($this->loadRemoteInstanceState()))
            return $this->pospond();

        foreach ($this->instanceData['NetworkInterfaces'] as $networkInterface)
        {
            if ($networkInterface['Attachment']['DeleteOnTermination'] === true)
            {
                continue;
            }

            if ($this->apiConnection->networkInterfaceExists($networkInterface['NetworkInterfaceId']))
            {
                $this->apiConnection->deleteNetworkInterface($networkInterface['NetworkInterfaceId']);
            }
        }

        if($this->instanceData['securityGroupIdToDelete']) {
            try{
                $this->apiConnection->deleteSecurityGroup(['GroupId' => $this->instanceData['securityGroupIdToDelete']]);
            } catch (Exception $e) {
                return $this->pospond();
            }
        }
        NetworkInterface::where([
            'service_id'=>$this->sid,
        ])->delete();
        return true;
    }

    public function loadRemoteInstanceState()
    {
        $instancesData = $this->apiConnection->describeInstanceStatus([
            'InstanceIds' => [$this->instanceData['InstanceId']],
            'IncludeAllInstances' => true
        ]);

        return $instancesData['InstanceStatuses'][0]['InstanceState']['Name'];
    }
    public function isInstanceInProperState($state = null)
    {
        if($state === 'terminated')
            return true;
        return false;
    }
}
