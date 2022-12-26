<?php

/* * ********************************************************************
 * HetznerVPS product developed. (2019-09-06)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\Servers\HetznerVps\App\Repositories\Cloud;

use ModulesGarden\Servers\HetznerVps\App\Repositories\AbstractProductConfigurationRepository;

/**
 * Description of ProductConfigurationRepository
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.0
 * @property $serverSockets
 * @property $serverCores
 * @property $serverVcpus
 * @property $serverCpulimit
 * @property $serverCpuunit
 * @property $serverMemory
 * @property $serverDiskSize
 * @property $serverIpv4
 * @property $serverIpv6
 * @property $serverSwap
 * @property $cpuunitsPriority1
 * @property $cpulimitPriority1
 */
class ProductConfigurationRepository extends AbstractProductConfigurationRepository
{

    public function isDebug()
    {
        return $this->get("debugMode") == "on";
    }

    public function getVirtualization()
    {
        return $this->get("virtualization", 'qemu');
    }

    public function isQemu()
    {
        return $this->getVirtualization() == "qemu";
    }

    public function isLxc()
    {
        return $this->getVirtualization() == "lxc";
    }

    public function getDefaultNode()
    {
        return $this->get("defaultNode");
    }

    public function isCheckResources()
    {
        return $this->get('checkResources') == "on";
    }

    public function isBackupVmBeforeReinstall()
    {
        return $this->get('backupVmBeforeReinstall') == "on";
    }

    public function isRebootVmAfterChangePackage()
    {
        return $this->get('rebootVmAfterChangePackage') == "on";
    }

    public function isDeleteBackups()
    {
        return $this->get('deleteBackups') == "on";
    }

    public function isServerNameservers()
    {
        return $this->get('serverNameservers') == "on";
    }

    public function getConsoleHost()
    {
        return $this->get("consoleHost");
    }

    public function getUserPrefix()
    {
        return $this->get("userPrefix");
    }

    public function getRealm()
    {
        return $this->get("realm", "pve");
    }

    public function getUserComment()
    {
        return $this->get("userComment");
    }

    public function getUserRole()
    {
        return $this->get("userRole");
    }

    public function getWelcomeEmailTemplateId()
    {
        return $this->get("welcomeEmailTemplateId");
    }

    public function getReinstallEmailTemplateId()
    {
        return $this->get("reinstallEmailTemplateId");
    }

    public function getServiceCreationFailedTemplateId()
    {
        return $this->get("serviceCreationFailedTemplateId");
    }

    public function getUpgradeNotificationTemplateId()
    {
        return $this->get("upgradeNotificationTemplateId");
    }

    public function isToDoList()
    {
        return $this->get("toDoList") == "on";
    }

    public function isCmode()
    {
        return $this->get("cmode") == "on";
    }

    public function getOsType()
    {
        return $this->get("ostype");
    }

    public function getPool()
    {
        return $this->get("pool");
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function getTty()
    {
        return $this->get("tty");
    }

    public function isSshKeyPairs()
    {
        return $this->get("sshKeyPairs") == "on";
    }

    public function getArch()
    {
        return $this->get("arch");
    }

    public function isConsole()
    {
        return $this->get("console") == "on";
    }

    public function isOnboot()
    {
        return $this->get("onboot") == "on";
    }

    public function isProtection()
    {
        return $this->get("protection") == "on";
    }

    public function isStartup()
    {
        return $this->get("startup") == "on";
    }

    public function isSshDeletePrivateKey()
    {
        return $this->get("sshDeletePrivateKey") == "on";
    }

    public function getOsTemplate()
    {
        return $this->get("osTemplate");
    }

    public function getCpuunits()
    {
        return $this->get("cpuunits");
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->get("memory");
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getStorageSize()
    {
        return $this->get("storageSize");
    }

    /**
     * @return int
     * @deprecated
     */
    public function getAdditionalDiskSize()
    {
        return $this->get("additionalDiskSize");
    }

    /**
     * @return int
     */
    public function getMinimumRate()
    {
        return $this->get("minimumRate");
    }

    /**
     * @return int
     */
    public function getIpv4()
    {
        return $this->get("ipv4");
    }

    /**
     * @return int
     */
    public function getIpv6()
    {
        return $this->get("ipv6");
    }

    /**
     * @return int
     */
    public function getBackupMaxFiles()
    {
        return $this->get("backupMaxFiles");
    }

    /**
     * @return int
     */
    public function getCpulimit()
    {
        return $this->get("cpulimit");
    }

    /**
     * @return int
     */
    public function getCores()
    {
        return $this->get("cores");
    }

    /**
     * @return int
     */
    public function getSwap()
    {
        return $this->get("swap");
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->get("rate");
    }

    /**
     * @return int
     */
    public function getBackupMaxSize()
    {
        return $this->get("backupMaxSize");
    }

    /**
     * @return int
     */
    public function getBandwidth()
    {
        return $this->get("bandwidth");
    }

    /**
     * @return int
     */
    public function getSnapshotMaxFiles()
    {
        return $this->get("snapshotMaxFiles");
    }

    /**
     * @return int
     */
    public function getMountPointStorage()
    {
        return $this->get("mountPointStorage");
    }

    /**
     * @return int
     */
    public function isMountPointRo()
    {
        return $this->get("mountPointRo") == "on";
    }

    /**
     * @return int
     */
    public function isReplicate()
    {
        return $this->get("replicate") == "on";
    }

    /**
     * @return int
     */
    public function getMountPointAcl()
    {
        return $this->get("mountPointAcl");
    }

    /**
     * @return int
     */
    public function isMountPointQuota()
    {
        return $this->get("mountPointQuota") == "on";
    }

    /**
     * @return string
     */
    public function getIpv4NetworkMode()
    {
        return $this->get("ipv4NetworkMode");
    }

    /**
     * @return string
     */
    public function getIpv6NetworkMode()
    {
        return $this->get("ipv6NetworkMode");
    }

    /**
     * @return string
     */
    public function getPrivateBridge()
    {
        return $this->get("privateBridge");
    }

    /**
     * @return string
     */
    public function getBridge()
    {
        return $this->get("bridge");
    }

    /**
     * @return boolean
     */
    public function isNetworkFirewall()
    {
        return $this->get("networkFirewall") == "on";
    }

    /**
     * @return int
     */
    public function getTagFrom()
    {
        return $this->get("tagFrom");
    }

    /**
     * @return int
     */
    public function getTagTo()
    {
        return $this->get("tagTo");
    }

    /**
     * @return string
     */
    public function getSwapUnit()
    {
        return $this->get("swapUnit", "mb");
    }

    /**
     * @return string
     */
    public function getStorageUnit()
    {
        return $this->get("storageUnit", "gb");
    }

    /**
     * @return string
     */
    public function getMemoryUnit()
    {
        return $this->get("memoryUnit", "mb");
    }

    /**
     * @return string
     */
    public function getAdditionalDiskUnit()
    {
        return $this->get("additionalDiskUnit", "gb");
    }

    /**
     * @return array
     */
    public function getFirewallInterfaces()
    {
        return $this->get("firewallInterfaces");
    }

    /**
     * @return int
     */
    public function getFirewallMaxRules()
    {
        return $this->get("firewallMaxRules");
    }

    /**
     * @return bool
     */
    public function isLoadBalancer()
    {
        return $this->get("loadBalancer") == "on";
    }

    /**
     * @return bool
     */
    public function isLoadBalancerShutdownOnUpgrade()
    {
        return $this->get("loadBalancerShutdownOnUpgrade") == "on";
    }

    /**
     * @return string
     */
    public function getLoadBalancerOnUpgrade()
    {
        return $this->get("loadBalancerOnUpgrade");
    }

    /**
     * @return bool
     */
    public function isLoadBalancerStopOnUpgrade()
    {
        return $this->get("loadBalancerStopOnUpgrade") == "on";
    }

    /**
     * @return string
     */
    public function getBackupStorage()
    {
        return $this->get("backupStorage");
    }

    /**
     * @return bool
     */
    public function isBackupRouting()
    {
        return $this->get("backupRouting") == "on";
    }

    /**
     * @return int
     */
    public function getBackupStoreDays()
    {
        return $this->get("backupStoreDays");
    }

    /**
     * @return string
     */
    public function getClusterState()
    {
        return $this->get("clusterState");
    }

    /**
     * @return int
     */
    public function getClusterMaxRestart()
    {
        return $this->get("clusterMaxRestart");
    }

    /**
     * @return string
     */
    public function getClusterGroup()
    {
        return $this->get("clusterGroup");
    }

    /**
     * @return int
     */
    public function getClusterMaxRelocate()
    {
        return $this->get("clusterMaxRelocate");
    }

    /**
     * @return bool
     */
    public function isPermissionStart()
    {
        return $this->get("permissionStart") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionStop()
    {
        return $this->get("permissionStop") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionNovnc()
    {
        return $this->get("permissionNovnc") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionXtermjs()
    {
        return $this->get("permissionXtermjs") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionOsTemplate()
    {
        return $this->get("permissionOsTemplate") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionIsoImage()
    {
        return $this->get("permissionIsoImage") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionGraph()
    {
        return $this->get("permissionGraph") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionBackupJob()
    {
        return $this->get("permissionBackupJob") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionNetwork()
    {
        return $this->get("permissionNetwork") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionFirewall()
    {
        return $this->get("permissionFirewall") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionDisk()
    {
        return $this->get("permissionDisk") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionReboot()
    {
        return $this->get("permissionReboot") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionShutdown()
    {
        return $this->get("permissionShutdown") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionSpice()
    {
        return $this->get("permissionSpice") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionReinstall()
    {
        return $this->get("permissionReinstall") == "on";
    }

    /**
     * @return array
     */
    public function getPermissionOsTemplates()
    {
        return $this->get("permissionOsTemplates");
    }

    public function isPermissionOsTemplates()
    {
        return !empty($this->get("permissionOsTemplates"));
    }

    /**
     * @return bool
     */
    public function isPermissionBackup()
    {
        return $this->get("permissionBackup") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionTaskHistory()
    {
        return $this->get("permissionTaskHistory") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionSnapshot()
    {
        return $this->get("permissionSnapshot") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionFirewallOption()
    {
        return $this->get("permissionFirewallOption") == "on";
    }

    public function getNetworkModel()
    {
        return $this->get("networkModel");
    }

    public function getNetworkPrivateModel()
    {
        return $this->get("networkPrivateModel");
    }

    public function isCloudInit()
    {
        return $this->get("cloudInit") == "on";
    }

    /**
     * @return int
     */
    public function getAdditionalDiskMbpsRd()
    {
        return $this->get("additionalDiskMbps_rd");
    }

    /**
     * @return int
     */
    public function getAdditionalDiskIopsRd()
    {
        return $this->get("additionalDiskIops_rd");
    }

    /**
     * @return int
     */
    public function getAdditionalDiskIopsWr()
    {
        return $this->get("additionalDiskIops_wr");
    }

    /**
     * @return int
     */
    public function getAdditionalDiskMbpsWr()
    {
        return $this->get("additionalDiskMbps_wr");
    }

    /**
     * @return int
     */
    public function getAdditionalDiskIopsRdMax()
    {
        return $this->get("additionalDiskIops_rd_max");
    }

    /**
     * @return int
     */
    public function getAdditionalDiskIopsWrMax()
    {
        return $this->get("additionalDiskIops_wr_max");
    }

    /**
     * @return string
     */
    public function getAdditionalDiskStorage()
    {
        return $this->get("additionalDiskStorage");
    }

    /**
     * @return string
     */
    public function getAdditionalDiskFormat()
    {
        return $this->get("additionalDiskFormat");
    }

    /**
     * @return string
     */
    public function getAdditionalDiskCache()
    {
        return $this->get("additionalDiskCache");
    }

    /**
     * @return bool
     */
    public function isAdditionalDiskIoThread()
    {
        return $this->get("additionalDiskIoThread") == "on";
    }

    /**
     * @return string
     */
    public function getAdditionalDiskType()
    {
        return $this->get("additionalDiskType");
    }

    /**
     * @return bool
     */
    public function isAdditionalDiskReplicate()
    {
        return $this->get("additionalDiskReplicate") == "on";
    }

    /**
     * @return bool
     */
    public function isAdditionalDiskDiscard()
    {
        return $this->get("additionalDiskDiscard") == "on";
    }

    /**
     * @return bool
     */
    public function isPermissionAdditionalDiskBackup()
    {
        return $this->get("permissionAdditionalDiskBackup") == "on";
    }

    public function isPermissionMountPointBackup()
    {
        return $this->get("permissionMountPointBackup") == "on";
    }

    public function isMountPointReplicate()
    {
        return $this->get("mountPointReplicate") == "on";
    }

    public function isOsTemplatesInAllNodes()
    {
        return $this->get("osTemplatesInAllNodes") == "on";
    }

    public function getPermissionIsoImages()
    {
        return $this->get("permissionIsoImages");
    }

    public function isPermissionIsoImages()
    {
        return !empty($this->get("permissionIsoImages"));
    }

    public function getPermissionSecondaryIsoImages()
    {
        return $this->get("permissionSecondaryIsoImages");
    }

    public function isPermissionSecondaryIsoImages()
    {
        return !empty($this->get("permissionSecondaryIsoImages"));
    }


    public function getStorage()
    {
        return $this->get("storage");
    }

    /**
     * @return bool
     */
    public function isAgent()
    {
        return $this->get('agent') == "on";
    }

    /**
     * @return string
     */
    public function getCdrom()
    {
        return $this->get('cdrom');
    }

    /**
     * @return bool
     */
    public function isNuma()
    {
        return $this->get('numa') == "on";
    }

    /**
     * @return bool
     */
    public function isSpec()
    {
        return $this->get('spec') == "on";
    }

    /**
     * @return bool
     */
    public function isFreeze()
    {
        return $this->get('freeze') == "on";
    }

    /**
     * @return string
     */
    public function getKeyboard()
    {
        return $this->get('keyboard');
    }

    /**
     * @return string
     */
    public function getVga()
    {
        return $this->get('vga');
    }

    /**
     * @return string
     */
    public function getClientNameForContainer()
    {
        return $this->get('clientNameForContainer');
    }

    /**
     * @return bool
     */
    public function isAcpi()
    {
        return $this->get('acpi') == "on";
    }

    /**
     * @return bool
     */
    public function isAutostart()
    {
        return $this->get('autostart') == "on";
    }

    /**
     * @return bool
     */
    public function isPcid()
    {
        return $this->get('pcid') == "on";
    }

    /**
     * @return bool
     */
    public function getHotplug()
    {
        return implode(",",$this->get('hotplug')) ;
    }

    /**
     * @return bool
     */
    public function isKvm()
    {
        return $this->get('kvm') == "on";
    }

    /**
     * @return bool
     */
    public function isReboot()
    {
        return $this->get('reboot') == "on";
    }

    /**
     * @return bool
     */
    public function isTablet()
    {
        return $this->get('tablet') == "on";
    }


    /**
     * @return string
     */
    public function getCloneMode()
    {
        return $this->get('cloneMode');
    }

    /**
     * @return string
     */
    public function getBalloon()
    {
        return $this->get('balloon');
    }

    /**
     * @return string
     */
    public function getArgs()
    {
        return $this->get('args');
    }

    /**
     * @return string
     */
    public function getMigrateSpeed()
    {
        return $this->get('migrate_speed');
    }

    /**
     * @return string
     */
    public function getMigrateDowntime()
    {
        return $this->get('migrate_downtime');
    }

    /**
     * @return string
     */
    public function getStartdate()
    {
        return $this->get('startdate');
    }

    /**
     * @return string
     */
    public function getShares()
    {
        return $this->get('shares');
    }

    /**
     * @return bool
     */
    public function isLocaltime()
    {
        return $this->get('localtime') == "on";
    }

    /**
     * @return string
     */
    public function getWatchdog()
    {
        return $this->get('watchdog');
    }

    /**
     * @return string
     */
    public function getStartup()
    {
        return $this->get('startup');
    }

    /**
     * @return bool
     */
    public function isTdf()
    {
        return $this->get('tdf') == "on";
    }

    /**
     * @return string
     */
    public function getSockets()
    {
        return $this->get('sockets');
    }

    /**
     * @return string
     */
    public function getVcpus()
    {
        return $this->get('vcpus');
    }

    /**
     * @return string
     */
    public function getIsoImage()
    {
        return $this->get('isoImage');
    }

    public function getCdromType()
    {
        return $this->get('cdromType');
    }

    /**
     * @return string
     */
    public function getDiskStorage()
    {
        return $this->get('diskStorage');
    }

    /**
     * @return string
     */
    public function getDiskFormat()
    {
        return $this->get('diskFormat');
    }

    /**
     * @return string
     */
    public function getScsihw()
    {
        return $this->get('scsihw');
    }

    /**
     * @return string
     */
    public function getDiskType()
    {
        return $this->get('diskType');
    }

    /**
     * @return string
     */
    public function getDiskCache()
    {
        return $this->get('diskCache');
    }

    /**
     * @return bool
     */
    public function isDiscard()
    {
        return $this->get('discard') == "on";
    }

    /**
     * @return bool
     */
    public function isIoThread()
    {
        return $this->get('ioThread') == "on";
    }

    /**
     * @return string
     */
    public function getMbpsRd()
    {
        return $this->get('mbps_rd');
    }

    /**
     * @return string
     */
    public function getIopsRd()
    {
        return $this->get('iops_rd');
    }

    /**
     * @return string
     */
    public function getIopsWr()
    {
        return $this->get('iops_wr');
    }

    /**
     * @return string
     */
    public function getMbpsWr()
    {
        return $this->get('mbps_wr');
    }

    /**
     * @return string
     */
    public function getIopsRdMax()
    {
        return $this->get('iops_rd_max');
    }

    /**
     * @return string
     */
    public function getIopsWrMax()
    {
        return $this->get('iops_wr_max');
    }

    /**
     * @return bool
     */
    public function isEtworkOneDevice()
    {
        return $this->get('etworkOneDevice') == "on";
    }

    /**
     * @return string
     */
    public function getQueues()
    {
        return $this->get('queues');
    }

    /**
     * @return string
     */
    public function getBootDevice1()
    {
        return $this->get('bootDevice1');
    }

    /**
     * @return string
     */
    public function getBootDevice2()
    {
        return $this->get('bootDevice2');
    }

    /**
     * @return string
     */
    public function getBootDevice3()
    {
        return $this->get('bootDevice3');
    }

    /**
     * @return string
     */
    public function getBootdisk()
    {
        return $this->get('bootdisk');
    }

    /**
     * @return bool
     */
    public function isCloudInitServicePassword()
    {
        return $this->get('cloudInitServicePassword') == "on";
    }

    /**
     * @return bool
     */
    public function isCloudInitServiceUsername()
    {
        return $this->get('cloudInitServiceUsername') == "on";
    }

    /**
     * @return bool
     */
    public function isCloudInitServiceNameservers()
    {
        return $this->get('cloudInitServiceNameservers') == "on";
    }

    /**
     * @return string
     */
    public function getCiuser()
    {
        return $this->get('ciuser');
    }

    public function getSearchdomain()
    {
        return $this->get('searchdomain');
    }

    public function isRandomHostname()
    {
        return $this->get('randomHostname') == "on";
    }

    public function isUnprivileged()
    {
        return $this->get('unprivileged') == "on";
    }

    public function isIpsetIpFilter()
    {
        return $this->get("ipsetIpFilter") == "on";
    }

    public function getBootOrder()
    {

        $boot = [];
        if ($this->getBootDevice1())
        {
            $boot[] = $this->getBootDevice1();
        }
        if ($this->getBootDevice2())
        {
            $boot[] = $this->getBootDevice2();
        }
        if ($this->getBootDevice3())
        {
            $boot[] = $this->getBootDevice3();
        }
        return implode("", $boot);
    }

    public function isOneNetworkDevice()
    {
        return $this->get("oneNetworkDevice") == "on";
    }


    public function getCpu()
    {
        return $this->get("cpu");
    }

    public function getSuspensionAction()
    {
        return $this->get("suspensionAction");
    }

    public function isSuspendOnBandwidthOverage()
    {
        return $this->get("suspendOnBandwidthOverage") == "on";
    }

    public function isStart()
    {
        return $this->get("start") == "on";
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->get("tags");
    }

    public function getCicustom()
    {
        return $this->get('cicustom');
    }

    /**
     * @return bool
     */
    public function isPermissionSshkeys()
    {
        return $this->get("permissionSshkeys") == "on";
    }

    public function isPermissionSnapshotJob()
    {
        return $this->get("permissionSnapshotJob") == "on";
    }

    public function getPermissionSnapshotJobPeriod()
    {
        return $this->get("permissionSnapshotJobPeriod");
    }

    public function getSnapshotJobs()
    {
        return $this->get("snapshotJobs");
    }

    public function isAgentTemplateUser()
    {
        return $this->get('agentTemplateUser') == "on";
    }

    public function isAgentPassword()
    {
        return $this->get('agentPassword') == "on";
    }

    public function isAgentConfigureNetwork()
    {
        return $this->get('agentConfigureNetwork') == "on";
    }

    /**
     * @return bool
     */
    public function isCloneOnTheSameStorage()
    {
        return $this->get('cloneOnTheSameStorage') == "on";
    }

    public function isFeatureKeyctl()
    {
        return $this->get('featureKeyctl') == "on";
    }

    public function isFeatureNesting()
    {
        return $this->get('featureNesting') == "on";
    }

    public function isFeatureNfs()
    {
        return $this->get('featureNfs') == "on";
    }

    public function isFeatureCifs()
    {
        return $this->get('featureCifs') == "on";
    }

    public function isFeatureFuse()
    {
        return $this->get('featureFuse') == "on";
    }

    public function isFeatureMknod()
    {
        return $this->get('featureMknod') == "on";
    }

    public function hasCpuFlags(){
        $configFlags =['md-clear', "pcid", "spec-ctrl", "ssbd", "ibpb", "virt-ssbd",
            "amd-ssbd", "amd-no-ssb", "pdpe1gb", "hv-tlbflush","hv-evmcs", "aes" ];
        foreach ($configFlags as $configFlag) {
            if($this->get($configFlag)=="on"){
                return true;
            }
        }
        return false;
    }

    public function getCpuFlagsAsSource(){
        $configFlags =['md-clear', "pcid", "spec-ctrl", "ssbd", "ibpb", "virt-ssbd",
          "amd-ssbd", "amd-no-ssb", "pdpe1gb", "hv-tlbflush","hv-evmcs", "aes" ];
        $cpuFlags=[];
        foreach ($configFlags as $configFlag) {
            if($this->get($configFlag)=="on"){
                $cpuFlags[] = "+". $configFlag;
            }
        }
        return implode(";", $cpuFlags);
    }

    public function isSsd()
    {
        return $this->get('ssd') == "on";
    }

    public function isAdditionalDiskSsd()
    {
        return $this->get('additionalDiskSsd') == "on";
    }

    /**
     * @return array
     */
    public function getPermissionFirewalOptions()
    {
        return (array) $this->get("permissionFirewalOptions");
    }

    public function isFirewalOptionEnable()
    {
        return $this->get("firewalOptionEnable")  == "on";
    }

    public function isFirewalOptionNdp()
    {
        return $this->get("firewalOptionNdp")  == "on";
    }

    public function isFirewalOptionMacfilter()
    {
        return $this->get("firewalOptionMacfilter")  == "on";
    }

    public function isFirewalOptionDhcp()
    {
        return $this->get("firewalOptionDhcp")  == "on";
    }

    public function isFirewalOptionRadv()
    {
        return $this->get("firewalOptionRadv")  == "on";
    }

    public function isFirewalOptionIpfilter()
    {
        return $this->get("firewalOptionIpfilter")  == "on";
    }

    public function getBwLimit(){
        return $this->get('bwlimit');
    }
    public function getBios(){
        return $this->get('bios');
    }

    public function isPermissionVirtualNetwork()
    {
        return $this->get("permissionVirtualNetwork")  == "on";
    }

    public function getLocations()
    {
        return $this->get("locations");
    }

    public function getPermissionOstype()
    {
        return $this->get("permissionOstype");
    }

    public function isPermissionUsername()
    {
        return $this->get("permissionUsername") == "on";
    }

    public function isPermissionPassword()
    {
        return $this->get("permissionPassword") == "on";
    }

    public function isPermissionNameservers()
    {
        return $this->get("permissionNameservers") == "on";
    }

    public function isPermissionSearchdomain()
    {
        return $this->get("permissionSearchdomain") == "on";
    }

    public function isPermissionIpv4()
    {
        return $this->get("permissionIpv4") == "on";
    }

    public function isPermissionIpv6()
    {
        return $this->get("permissionIpv6") == "on";
    }

    public function isPermissionSockets()
    {
        return $this->get("permissionSockets") == "on";
    }

    public function isPermissionCores()
    {
        return $this->get("permissionCores") == "on";
    }

    public function isPermissionVcpus()
    {
        return $this->get("permissionVcpus") == "on";
    }

    public function isPermissionCpuLimit()
    {
        return $this->get("permissionCpuLimit") == "on";
    }

    public function isPermissionCpuunits()
    {
        return $this->get("permissionCpuunits") == "on";
    }

    public function isPermissionSwap()
    {
        return $this->get("permissionSwap") == "on";
    }


    public function __get($name)
    {
        if(preg_match("/server/", $name)){
            $data = new \stdClass();
            $data->min = 0;
            $data->max = 0;
            $value = $this->get($name);
            if(is_null($value)){
                return $data;
            }
            list($data->min, $data->max)  = explode("-", $this->get($name));
            return $data;
        }
        return  $this->get($name);
    }

    public function getVirtualNetworks(){
        return $this->get('virtualNetworks');
    }

    public function getButtonSyle()
    {
        return $this->get("buttonSyle");
    }

    public function hasCpuPriority(){
        for($i=1; $i<=5; $i++) {
            if(empty($this->get('cpuunitsPriority'.$i))){
                return false;
            }
            if(empty($this->get('cpulimitPriority'. $i))){
                return false;
            }
        }
        return true;
    }

    public function isPermissionCustomTemplates(){
        return $this->get('permissionCustomTemplates')=='on';
    }

    public function isDetailsCombinedView()
    {
        return $this->get("detailsView")=='combined';
    }
}
