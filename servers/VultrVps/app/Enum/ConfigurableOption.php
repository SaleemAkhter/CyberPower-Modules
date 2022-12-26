<?php

namespace ModulesGarden\Servers\VultrVps\App\Enum;

use ModulesGarden\Servers\VultrVps\Core\Enum\Enum;

final  class ConfigurableOption extends Enum
{
    const  OS_ID       = 'os_id';
    const  REGION       = 'region';
    const  PLAN = 'plan';
    const SNAPSHOT = 'snapshot_id';
    const ISO_ID = 'iso_id';
    const IPV6 = 'ipv6';
    const BACKUPS = 'backups';
    const APPLICATION = 'app_id';
}