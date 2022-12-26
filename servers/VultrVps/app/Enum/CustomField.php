<?php

namespace ModulesGarden\Servers\VultrVps\App\Enum;

use ModulesGarden\Servers\VultrVps\Core\Enum\Enum;

class CustomField extends Enum
{
    const INSTANCE_ID = 'instanceId';
    const FIREWALL_GROUP_ID = 'firewallGroupId';
    const SSH_KEY     = 'sshKey';
    const LABEL  = 'label';

}