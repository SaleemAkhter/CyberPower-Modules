<?php

namespace ModulesGarden\Servers\AwsEc2\App\Jobs;

use ModulesGarden\Servers\AwsEc2\App\Helpers\CustomFields\ProductCustomFields;

class GetWindowsPassword extends VmCreated
{
    protected $pid = null;
    protected $sid = null;
    protected $instanceId = null;

    public function handle($pid = null, $sid = null, $instanceId = null, $ipv4Count = 0, $tags = null, $securityGroupIds = null)
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->instanceId = $instanceId;

        return $this->runTask();
    }

    public function runTaskActions()
    {
        $password = $this->loadWindowsPassword();
        if (!$password || trim($password) === '')
        {
            return $this->pospond();
        }

        $this->updatePasswordField($password);

        return true;
    }

    public function updatePasswordField($password = null)
    {
        $this->updateCustomField('windowsPassword|Windows Password', $password);
    }

    public function loadWindowsPassword()
    {
        $windowsPassword = $this->apiConnection->getWindowsPassword($this->instanceId);

        return $windowsPassword;
    }

    public function updateCustomField($fieldName = null, $fieldValue = null)
    {
        $prodModel = new ProductCustomFields($this->pid, $this->sid);

        $prodModel->updateFieldValue($fieldName, $fieldValue);
    }
}
