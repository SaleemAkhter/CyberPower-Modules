<?php
namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Enum\Enum;

class CustomField extends  Enum
{
    const INSTANCE_ID = 'instanceId';
    const ZONE = 'zone';
    const REGION = 'region';
    const SSH_KEY     = 'sshKey';

}