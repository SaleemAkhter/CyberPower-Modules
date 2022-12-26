<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\TaskHistory;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 */
class AvailableTask
{

    const SNAPSHOT_CREATED             = 'snapshotCreated';
    const SNAPSHOT_DELETED             = 'snapshotDeleted';
    const SNAPSHOT_RESTORED            = 'snapshotRestored';
    const VM_REBUILD                   = 'vmRebuild';
    const VM_POWER_OFF                 = 'powerOff';
    const VM_POWER_ON                  = 'powerOn';
    const VM_REBOOT                    = 'reboot';
    const VM_SHUTDOWN                  = 'shutdown';
    const VM_RESIZE                    = 'resize';
    const VM_CREATE                   = 'create';
    const VM_PASSWORD_RESET            = 'passwordReset';
    const VM_BACKUPS_ENABLE            = 'backupsEnable';
    const VM_BACKUPS_DISABLED          = 'backupsDisable';
    const VM_IPV6_ENABLE               = 'ipv6Enable';
    const VM_PRIVATE_NETWORKING_ENABLE = 'privateNetworkingEnable';

}
