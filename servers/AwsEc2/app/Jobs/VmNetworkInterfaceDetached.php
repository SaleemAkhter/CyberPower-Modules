<?php

namespace ModulesGarden\Servers\AwsEc2\App\Jobs;

class VmNetworkInterfaceDetached extends VmChangedPackage
{
    protected $networkInterfaceId = null;

    public function handle($pid = null, $sid = null, $instanceId = null, $ipv4Count = 0, $tags = null, $securityGroupIds = null)
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->instanceId = $instanceId;

        if (is_string($networkInterfaceId) && trim($networkInterfaceId) !== '')
        {
            $this->networkInterfaceId = $networkInterfaceId;
        }

        return $this->runTask();
    }

    public function runTaskActions()
    {
        if ($this->apiConnection->networkInterfaceExists($this->networkInterfaceId) && $this->networkInterfaceId !== null)
        {
            $this->apiConnection->deleteNetworkInterface($this->networkInterfaceId);
        }

        return true;
    }
}
