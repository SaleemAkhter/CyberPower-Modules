<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (file_exists(__DIR__ . '/manage_cfields.php'))
    require_once __DIR__ . '/manage_cfields.php';

if (file_exists(__DIR__ . '/class/class.php'))
    require_once __DIR__ . '/class/class.php';

if (file_exists(__DIR__ . '/class/obj_to_array.php'))
    require_once __DIR__ . '/class/obj_to_array.php';

class vmware
{

    var $vsphere;
    var $server;
    var $user;
    var $password;
    var $instance;
    var $WgsVmwareObj;
    var $ObjToArray;

    function __construct($ipaddress = null, $username = null, $password = null)
    {
        $this->server = $ipaddress;
        $this->user = $username;
        $this->password = $password;
        $this->WgsVmwareObj = new WgsVmware();
        $this->ObjToArray = new ObjToArray();
    }

    public function connect()
    {
        $autoloader = new \Vmwarephp\Autoloader;
        $autoloader->register();
        $this->vsphere = new \Vmwarephp\Vhost($this->server, $this->user, $this->password);
    }

    public function WGS_vSphereCookies()
    {
        $this->connect();
        $about = $this->vsphere;
        return $about;
    }

    public function WGS_vSphereVersion()
    {
        $this->connect();
        $about = $this->vsphere->getAbout();
        return $about->version;
    }

    public function WGS_vSphereBuild()
    {
        $this->connect();
        $about = $this->vsphere->getAbout();
        return $about->build;
    }

    public function WGS_vSphereUUID()
    {
        $this->connect();
        $about = $this->vsphere->getAbout();
        return $about->instanceUuid;
    }

    public function list_networks()
    {
        $this->connect();
        return $this->vsphere->findAllManagedObjects('Network', array('parent', 'name', 'summary', 'host', 'vm'));
    }

    public function customfunction()
    {
        $this->connect();
        return $this->vsphere->findManagedObjectByName('Datacenter', 'ha-datacenter', array('parent', 'name'));
    }

    public function get_datacenter_networks($datacenter_name)
    {
        $this->connect();
        $datacenter = $this->get_datacenter($datacenter_name);
        $childEntitys = $this->vsphere->findOneManagedObject('Folder', $datacenter->networkFolder->reference->_, array('parent', 'childEntity'));

        $data['networks'] = array();
        foreach ($childEntitys->childEntity as $nw) {
            if ($nw->reference->type == 'Network') {
                $networks = $this->vsphere->findOneManagedObject('Network', $nw->reference->_, array('parent', 'name'));
                $data['networks'][$nw->reference->_]['name'] = $networks->name;
                $data['networks'][$nw->reference->_]['id'] = $networks->reference->_;
            }
        }
        return $data;
    }

    public function list_resouce_pools()
    {
        $this->connect();
        return $this->vsphere->findAllManagedObjects('ResourcePool', array('parent', 'name', 'summary'));
    }

    public function getAllHosts()
    {
        $this->connect();
        return $this->vsphere->findAllManagedObjects('HostSystem', array('parent', 'name'));
    }

    public function getHostnames($host = null)
    {
        $this->connect();
        if ($host)
            return $this->vsphere->findManagedObjectByName('HostSystem', $host, array('name', 'parent', 'summary.host', 'datastore'));
        else
            return $this->vsphere->findAllManagedObjects('HostSystem', array('parent', 'name', 'hardware'));
    }

    public function getResourcePoolVm($vm)
    {
        $this->connect();
        return $this->vsphere->findManagedObjectByName('VirtualMachine', $vm, array('parent', 'name', 'resourcePool'));
    }

    public function getexitingHostVm($vm)
    {
        $this->connect();
        return $this->vsphere->findManagedObjectByName('VirtualMachine', $vm, array('parent', 'name', 'summary'));
    }

    public function getDatastoreInfo($datastore_id, $type = null)
    {
        $this->connect();
        if (!empty($type) && $type == 'name')
            $functionName = 'findManagedObjectByName';
        else
            $functionName = 'findOneManagedObject';
        return $this->vsphere->$functionName('Datastore', $datastore_id, array('name'));
    }

    public function list_clusters()
    {
        $this->connect();
        return $this->vsphere->findAllManagedObjects('ClusterComputeResource', array('parent', 'name', 'configuration', 'summary', 'host', 'datastore'));
    }

    public function get_cluster_datacenter($cluster)
    {
        $this->connect();
        $cluster_object = $this->get_cluster_info($cluster);

        $parent_folder = $this->vsphere->findOneManagedObject('Folder', $cluster_object->parent->reference->_, array('parent', 'name'));

        $parent_dc = $this->vsphere->findOneManagedObject('Datacenter', $parent_folder->parent->reference->_, array('parent', 'name'));

        return $parent_dc;
    }

    public function list_datastores($cluster_name = NULL)
    {
        $this->connect();

        if (!empty($cluster_name)) {
            $cluster_object = $this->get_cluster_info($cluster_name);
            return $cluster_object->findAllManagedObjects('Datastore', array('parent', 'name', 'summary'));
        } else {
            return $this->vsphere->findAllManagedObjects('Datastore', array('parent', 'name', 'summary'));
        }
    }

    public function get_cluster_info($cluster_name)
    {
        $this->connect();
        $cluster_info = $this->vsphere->findManagedObjectByName("ClusterComputeResource", $cluster_name, array('parent', 'name', 'configuration', 'summary'));
        return $cluster_info;
    }

    public function get_datacenter($datacenter_name)
    {
        $this->connect();
        return $this->vsphere->findManagedObjectByName('Datacenter', $datacenter_name, array('parent', 'name', 'hostFolder', 'networkFolder', 'network'));
    }

    public function get_datacenter_clusters($datacenter_name)
    {
        $this->connect();
        $datacenter = $this->get_datacenter($datacenter_name);
        $childEntitys = $this->vsphere->findOneManagedObject('Folder', $datacenter->hostFolder->reference->_, array('parent', 'childEntity'));

        $data['clusters'] = array();
        foreach ($childEntitys->childEntity as $cr) {
            if ($cr->reference->type == 'ClusterComputeResource') {
                $cluster = $this->vsphere->findOneManagedObject('ClusterComputeResource', $cr->reference->_, array('parent', 'name'));
                $data['clusters'][$cr->reference->_]['name'] = $cluster->name;
                $data['clusters'][$cr->reference->_]['id'] = $cluster->reference->_;
            }
        }
        return $data;
    }

    public function list_datacenters()
    {
        $this->connect();
        return $this->vsphere->findAllManagedObjects('Datacenter', array('parent', 'name'));
    }

    public function get_network($network_id)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('Network', $network_id, array('name', 'summary'));
    }

    public function get_dv_port_group($network_id)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('DistributedVirtualPortgroup', $network_id, array('config', 'key', 'portKeys', 'summary'));
    }

    public function getResourcePoolName($resourceObj)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('ResourcePool', $resourceObj, array('name'));
    }

    public function get_host_parent($hostObj = null)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('parent', 'name'));
    }

    public function get_host_resource_pool($host_parent, $hostType)
    {
        //        return $this->vsphere->findOneManagedObject('ComputeResource', $host_parent, array('resourcePool'));
        return $this->vsphere->findOneManagedObject($hostType, $host_parent, array('resourcePool'));
    }

    public function list_datacenters_host($dc)
    {
        $this->connect();
        $datacenter = $this->vsphere->findManagedObjectByName('Datacenter', $dc, array('parent', 'name', 'hostFolder')); //$this->get_datacenter($dc);

        $childEntitys = $this->vsphere->findOneManagedObject('Folder', $datacenter->hostFolder->reference->_, array('parent', 'childEntity'));

        $data = array();
        foreach ($childEntitys->childEntity as $key => $child) {
            if ($child->reference->type) {
                if (strchr($child->reference->type, 'ClusterComputeResource')) {
                    $hostObj = $this->vsphere->findOneManagedObject('ClusterComputeResource', $child->reference->_, array('parent', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                    $data[] = $hostObj->host;
                    // $networksArr = $this->WgsVmwareObj->vmware_object_to_array($networks);
                    // foreach ($networksArr['RetrievePropertiesResponse']['returnval']['propSet'] as $networksValue) {
                    //     if ($networksValue['name'] == 'summary') {
                    //         $numHosts = $networksValue['val']['numHosts'];
                    //     }
                    // }
                    // if ($numHosts > 0)
                    //     $networks = $this->vsphere->findOneManagedObject('ClusterComputeResource', $child->reference->_, array('parent', 'host', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                    // $data[] = $networks;//$this->WgsVmwareObj->vmware_object_to_array($networks);
                } elseif (strchr($child->reference->type, 'ComputeResource')) {
                    $hostObj = $this->vsphere->findOneManagedObject('ComputeResource', $child->reference->_, array('parent', 'name', 'host'));
                    $data[] = $hostObj->host;
                    //print_r($networks->host);
                    // foreach($networks->host as $hostRes){
                    //     echo $hostRes->name;
                    //     echo $hostRes->reference->_;
                    // }die;
                    // $networksArr = $this->WgsVmwareObj->vmware_object_to_array($networks);
                    // foreach ($networksArr['RetrievePropertiesResponse']['returnval']['propSet'] as $networksValue) {
                    //     if ($networksValue['name'] == 'summary') {
                    //         $numHosts = $networksValue['val']['numHosts'];
                    //     }
                    // }
                    // if ($numHosts > 0)
                    //     $networks = $this->vsphere->findOneManagedObject('ComputeResource', $child->reference->_, array('parent', 'host', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                    // $data[] = $networks;//$this->WgsVmwareObj->vmware_object_to_array($networks);
                } elseif (strchr($child->reference->type, 'Folder')) {
                    $folders = $this->vsphere->findOneManagedObject('Folder', $child->reference->_, array('parent', 'childEntity', 'childType'));
                    foreach ($folders->childEntity as $childEntity) {
                        if ($childEntity->reference->type == 'ComputeResource') {
                            $hostObj = $this->vsphere->findOneManagedObject('ComputeResource', $childEntity->reference->_, array('parent', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                            $data[] = $hostObj->host;
                            // $networksArr = $this->WgsVmwareObj->vmware_object_to_array($networks);
                            // foreach ($networksArr['RetrievePropertiesResponse']['returnval']['propSet'] as $networksValue) {
                            //     if ($networksValue['name'] == 'summary') {
                            //         $numHosts = $networksValue['val']['numHosts'];
                            //     }
                            // }
                            // if ($numHosts > 0)
                            //     $networks = $this->vsphere->findOneManagedObject('ComputeResource', $childEntity->reference->_, array('parent', 'host', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                            // $data[] = $networks;//$this->WgsVmwareObj->vmware_object_to_array($networks);
                        } elseif ($childEntity->reference->type == 'ClusterComputeResource') {
                            $hostObj = $this->vsphere->findOneManagedObject('ClusterComputeResource', $childEntity->reference->_, array('parent', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                            $data[] = $hostObj->host;
                            // $networksArr = $this->WgsVmwareObj->vmware_object_to_array($networks);
                            // foreach ($networksArr['RetrievePropertiesResponse']['returnval']['propSet'] as $networksValue) {
                            //     if ($networksValue['name'] == 'summary') {
                            //         $numHosts = $networksValue['val']['numHosts'];
                            //     }
                            // }
                            // if ($numHosts > 0)
                            //     $networks = $this->vsphere->findOneManagedObject('ClusterComputeResource', $childEntity->reference->_, array('parent', 'host', 'name', 'resourcePool', 'network', 'datastore', 'summary'));
                            // $data[] = $networks;//$this->WgsVmwareObj->vmware_object_to_array($networks);
                        }
                    }
                }
            } else {
                return null;
            }
        }
        return $data;
    }

    public function getNetworkName()
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('parent', 'name', 'datastore', 'network', 'summary.host', 'vm', 'hardware')); //systemResources
    }

    public function getHostInfo($hostObj)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('name')); //systemResources
    }

    public function get_host_resources($hostObj = null)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('parent', 'name', 'datastore', 'network', 'summary', 'vm', 'hardware')); //systemResources
    }

    public function get_host_network($hostObj = null)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('parent', 'name', 'network')); //systemResources
    }

    public function get_host_datastores($hostObj = null, $type = null)
    {
        $this->connect();
        if (!empty($type))
            return $this->vsphere->findManagedObjectByName('HostSystem', $hostObj, array('parent', 'name', 'datastore'));
        else
            return $this->vsphere->findOneManagedObject('HostSystem', $hostObj, array('parent', 'name', 'datastore')); //systemResources
    }

    public function get_vm_info_by_obj($vm_obj)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('VirtualMachine', $vm_obj, array('parent', 'name', 'runtime.powerState'));
    }

    public function datastoreDetail($obj)
    {
        $this->connect();
        return $this->vsphere->findOneManagedObject('Datastore', $obj, array('name'));
    }

    public function getVmDetail($vmId)
    {
        $this->connect();
        $vm_info = $this->vsphere->findOneManagedObject('VirtualMachine', $vmId, array('name', 'runtime.powerState'));
        return $vm_info;
    }

    public function migrate_vm($vm_name, $host_sys_name, $resource_pool, $vm_state, $vm_priority)
    {
        try {
            $this->connect();
            $host = $this->vsphere->findManagedObjectByName('HostSystem', $host_sys_name, array('name'));
            $pool = $this->vsphere->findOneManagedObject('ResourcePool', $resource_pool, array('name'));
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('summary'));
            if (empty($vm_info)) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $args = array('pool' => $pool->reference, 'host' => $host->reference, 'priority' => $vm_priority, 'state' => $vm_state);

            $ret = $vm_info->MigrateVM_Task($args);
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }
        return array('state' => $state, 'obj' => $ret);
    }

    public function cloneLinuxVm($cloneArr, $params, $customFields = null)
    {
        $hostsystem_name = $cloneArr['hostsystem_name'];
        $reinstall = $cloneArr['reinstall'];
        $dcObj = $cloneArr['dcobj'];
        $networkIp = $cloneArr['networkIp'];
        $memoryMB = $cloneArr['memoryMB'];
        $numCPUs = $cloneArr['numCPUS'];
        $cpuMhz = $cloneArr['cpuMhz'];
        $datastore_id = $cloneArr['datastore_id'];
        $disk_drives = $cloneArr['disk_drives'];
        $cloneVmName = $cloneArr['templatename'];
        $newVmname = $cloneArr['newVmname'];
        $macaddress = trim($cloneArr['macaddress']);
        $network_adapters = $cloneArr['network_adapters'];
        $autoConfiguration = $cloneArr['autoConfiguration'];
        $dhcp = $cloneArr['dhcp'];
        $dns = $cloneArr['dns'];
        $ip = $networkIp;
        $gateway = $cloneArr['gateway'];
        $netmask = $cloneArr['netmask'];
        $serviceid = $params['serviceid'];
        $cloneVmPassword = $cloneArr['existingpw'];
        $ipListArr = $cloneArr['ipListArr'];
        $osType = $cloneArr['osType'];
        $guetOsVersion = $cloneArr['guetOsVersion'];
        $osport = $cloneArr['osport'];
        $_LANG = $cloneArr['_LANG'];

        try {
            $this->connect();

            $vncPw = $this->vmwareGenerateStrongPassword();

            $checkPort = $this->checkVmPortInDb($reinstall);

            $existWebSocifyPort = $checkPort['websockifyport'];

            $existVncPort = $checkPort['vncport'];

            if (empty($existVncPort))
                $vnc_port = 50000;
            else
                $vnc_port = $existVncPort + 1;

            if (empty($existWebSocifyPort))
                $webSocifyPort = 1000;
            else
                $webSocifyPort = $existWebSocifyPort + 1;

            $host = $this->vsphere->findManagedObjectByName('HostSystem', $hostsystem_name, array('name'));

            $host_obj = $host->parent->reference->_;

            $hostType = $host->parent->reference->type;
            $resourceRef = $this->get_host_resource_pool($host_obj, $hostType)->resourcePool->reference->_;

            $pool = $this->vsphere->findOneManagedObject('ResourcePool', $resourceRef, array('name'));

            $datacenter = $this->vsphere->findOneManagedObject('Datacenter', $dcObj, array('parent', 'name'));

            $folder = $datacenter->getVmFolder();
            $config = $this->reconfigureConfig($memoryMB, $numCPUs, $vncPw, $vnc_port, $webSocifyPort, $cpuMhz, $params, $networkIp);

            $datastore = $this->vsphere->findOneManagedObject('Datastore', $datastore_id, array('name'));

            //$cms = new VirtualMachineConfigSpec();

            $vmDevices = $this->vsphere->findManagedObjectByName('VirtualMachine', $cloneVmName, array('config.hardware.device'));

            $scsi_controller_key = $virtual_device_count = 1000;
            $scsi_controller_function = '';
            foreach ($vmDevices as $key => $dev) {
                if (is_array($dev)) {
                    foreach ($dev as $dKey => $device) {
                        if ($device instanceof VirtualLsiLogicController) {
                            $scsi_controller_function = 'VirtualLsiLogicController';
                        } elseif ($device instanceof VirtualLsiLogicSASController) {
                            $scsi_controller_function = 'VirtualLsiLogicSASController';
                        } elseif ($device instanceof ParaVirtualSCSIController) {
                            $scsi_controller_function = 'ParaVirtualSCSIController';
                        } elseif ($device instanceof VirtualBusLogicController) {
                            $scsi_controller_function = 'VirtualBusLogicController';
                        }

                        if ($device instanceof VirtualE1000) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualE1000';
                        } elseif ($device instanceof VirtualE1000e) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualE1000e';
                        } elseif ($device instanceof VirtualVmxnet3) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualVmxnet3';
                        } elseif ($device instanceof VirtualPcnet32) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualPcnet32';
                        } elseif ($device instanceof VirtualSriovEthernetCard) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualSriovEthernetCard';
                        } elseif ($device instanceof Virtualvlance) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'Virtualvlance';
                        }
                    }
                }
            }
            $virtual_devices = array();
            $virtual_devices[] = $this->create_scsi_controller_spec($scsi_controller_key, 0, $scsi_controller_function);
            $disk_number = 0;
            $totalDisk = 0;
            foreach ($disk_drives as $disk_info) {
                $totalDisk = $totalDisk + $disk_info['capacity'];
                $virtual_devices[] = $this->create_disk_spec_edit($disk_info['capacity'], $datastore_id, $disk_number, $scsi_controller_key, $cloneVmName, $newVmname);
                ++$virtual_device_count;
                ++$disk_number;
            }

            if (!empty($dhcp) && !empty($reinstall)) {
                $virtual_devices[] = $this->create_mac_nic_spec($networkName, $networksumary, $nickey, $macaddress, $function, $networkLabel, $existingMac, $dhcp, $existingDevice);
                //$virtual_devices[] = $this->create_mac_nic_spec($networkName, $networksumary, $nickey, $existingMac, $function, $networkLabel, $existingMac, $dhcp, $existingDevice);
            } else {
                //            if ($autoConfiguration) {
                if (!empty($nickey) && !empty($networkName) && !empty($networksumary) && $networkLabel) {
                    $virtual_devices[] = $this->remove_mac_nic_spec($networkName, $networksumary, $nickey, $macaddress, $function, $networkLabel);
                }
                //            }
                if (!empty($macaddress)) {
                    $macAddress = $macaddress;
                } else {
                    $macAddress = '';
                }
                $nic_number = 0;
                foreach ($network_adapters as $network_adapter) {
                    $nic_name = 'nic_' . $nic_number;
                    if (strchr($network_adapter['network'], 'dvportgroup')) {
                        $network = $this->get_dv_port_group($network_adapter['network']);
                        $portGroup = $network->toReference()->_;
                        $dvs = $network->config->distributedVirtualSwitch->toReference()->_;
                        $vmDevices = $this->vsphere->findOneManagedObject('VmwareDistributedVirtualSwitch', $dvs, array('uuid'));
                        $switchUuid = $vmDevices->uuid;
                        $virtual_devices[] = $this->create_dvs_nic_spec($network->name, $nic_name, $macAddress, $function, $portGroup, $switchUuid);
                        ++$virtual_device_count;
                        ++$nic_number;
                    } else {
                        $network = $this->get_network($network_adapter['network']);
                        $virtual_devices[] = $this->create_nic_spec($network->name, $nic_name, $network_adapter['network'], $macAddress, $function);
                        ++$virtual_device_count;
                        ++$nic_number;
                    }
                }
            }
            $config->deviceChange = $virtual_devices;

            $productID = "";

            $cloneSpec = new VirtualMachineCloneSpec();
            $location = new VirtualMachineRelocateSpec();
            // if (!empty($fromtemplate)) {
            $location->pool = $pool->reference;
            $location->host = $host->reference;
            $diskLocators = new VirtualMachineRelocateSpecDiskLocator();
            $diskLocators->datastore = $datastore->reference;
            // $diskBackingInfo = new VirtualDeviceBackingInfo();
            // $diskLocators->diskBackingInfo = $diskBackingInfo;
            $diskLocators->diskId = '2000';
            // $diskLocators->diskMoveType = "VirtualMachineRelocateDiskMoveOptions.moveAllDiskBackingsAndConsolidate";
            // print_r($diskLocators);
            // die;
            $location->disk = $diskLocators;
            //  $mvOptn = new VirtualMachineRelocateDiskMoveOptions();
            // $location->diskMoveType = $mvOptn->createNewChildDiskBacking;
            // $location->DiskMoveType = "VirtualMachineRelocateDiskMoveOptions.CREATE_NEW_CHILD_DISK_BACKING.value()";
            // }
            $location->datastore = $datastore_id;
            $cloneSpec->location = $location;
            $cloneSpec->powerOn = true;
            $cloneSpec->template = false;
            $cloneSpec->config = $config;

            //if ($autoConfiguration && empty($dhcp)) {

            $customSpec = new CustomizationSpec();
            $linuxOptions = new CustomizationLinuxOptions();
            $customSpec->options = $linuxOptions;
            $linuxPrep = new CustomizationLinuxPrep();
            $linuxPrep->hwClockUTC = true;
            $linuxPrep->timeZone = "Europe/London";

            $fixedName = new CustomizationFixedName();

            $fixedName->name = $this->WgsVmwareObj->vmwareClean($newVmname);
            $fixedName->name = str_replace('.', '', $fixedName->name);
            $fixedName->name = str_replace('-', '', $fixedName->name);

            $linuxPrep->hostName = $fixedName;
            $customSpec->identity = $linuxPrep;
            if ($autoConfiguration && empty($dhcp)) {
                $globalIPSettings = new CustomizationGlobalIPSettings();
                $globalIPSettings->dnsServerList = explode(',', $dns);
                $customSpec->globalIPSettings = $globalIPSettings;

                $fixedIp = new CustomizationFixedIp();
                $fixedIp->ipAddress = $ip;

                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = $fixedIp;
                $customizationIPSettings->gateway = array($gateway);
                $customizationIPSettings->subnetMask = $netmask;

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
                $cloneSpec->customization = $customSpec;
            } elseif (!empty($dhcp)) {
                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = new CustomizationDhcpIpGenerator();

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
                $cloneSpec->customization = $customSpec;
            }

            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $cloneVmName, array('configStatus'));
            $arg = array('folder' => $folder->reference, 'name' => $newVmname, 'spec' => $cloneSpec);

            $ret = $vm_info->cloneVM_Task($arg);
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }

            $uid = $params['userid'];
            if ($autoConfiguration && empty($dhcp)) {
                if (!empty($reinstall)) {
                    $query = Capsule::table('mod_vmware_pw_linux_vm')->where('uid', (int) $uid)->where('sid', (int) $serviceid)->where('pid', $params['pid'])->first();
                    if ($query->password)
                        $rootPw = $this->ObjToArray->wgsvmwarePwdecryption($query->password);
                    else
                        $rootPw = $this->vmwareGenerateStrongPassword(15);
                } else
                    $rootPw = $this->vmwareGenerateStrongPassword(15);
            } else
                $rootPw = $cloneVmPassword;
            $vmPassword = $rootPw;
            if (empty($reinstall)) {
                logModuleCall("VMware", "Create linux/Others VM from existing VM", $arg, $this->WgsVmwareObj->vmware_object_to_array($ret));
            } elseif ($reinstall) {
                logModuleCall("VMware", "Create linux/Others VM from existing VM (Reinstall)", $arg, $this->WgsVmwareObj->vmware_object_to_array($ret));
            }
            if ($ret->info->state == 'success') {
                sleep(5);
                $vmInfo = $this->get_vm_hardware($newVmname);
                $existingMacAddress = "";
                foreach ($vmInfo as $key => $dev) {
                    if (is_array($dev)) {
                        foreach ($dev as $dKey => $device) {
                            if ($device->macAddress) {
                                $existingMacAddress = $device->macAddress;
                                break;
                            }
                        }
                    }
                }

                if (!empty($dhcp) && empty($reinstall)) {
                    try {
                        if (Capsule::table('mod_vmware_vm_ip')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count() > 0)
                            Capsule::table('mod_vmware_vm_ip')->update(['status' => '0']);
                        else
                            Capsule::table('mod_vmware_vm_ip')->insert(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'status' => '0']);
                    } catch (\Exception $ex) {
                        logActivity("Could not insert data in table mod_vmware_vm_ip. Error: {$ex->getMessage()}");
                    }
                }

                $status = 'success';

                $adminQuery = Capsule::table('tbladmins')->select('id')->first();
                $admin = $adminQuery->id;

                if (empty($reinstall)) {
                    $this->vmwareAssignIp($ipListArr, $newVmname, $dhcp, $params['serviceid']);
                }

                $params['vm_password'] = $rootPw;
                $this->vmwareUpdateServiceField($serviceid, $newVmname, $ip, $params, $vnc_port, $vncPw, $admin, $webSocifyPort, $dcObj, $hostsystem_name, $osport, $reinstall, $osType, $guetOsVersion, $customFields, $dhcp, $existingMacAddress);   #update vnc detail , IP and vmware in service field
                $this->updateVmPortInDb($webSocifyPort, $vnc_port);

                $new_vm_name = $newVmname;
                $networkIp = $ip;
                $serviceId = $serviceid;
                unset($ipListArr[0]);

                // if (empty($reinstall)) {
                try {
                    if (!Capsule::Schema()->hasTable('mod_vmware_pw_linux_vm')) {
                        Capsule::schema()->create(
                            'mod_vmware_pw_linux_vm',
                            function ($table) {
                                $table->increments('id');
                                $table->string('vm_name', '150');
                                $table->string('old_password', '255');
                                $table->string('password', '150');
                                $table->string('ip');
                                $table->integer('uid');
                                $table->integer('sid');
                                $table->integer('pid');
                                $table->string('os', '150');
                                $table->string('ram', '150');
                                $table->string('hdd', '150');
                                $table->string('cpu', '150');
                                $table->integer('status');
                                $table->string('os_version')->nullable();
                                $table->string('port')->nullable();
                            }
                        );
                    }

                    Capsule::Schema()->table('mod_vmware_pw_linux_vm', function ($table) {
                        if (!Capsule::Schema()->hasColumn('mod_vmware_pw_linux_vm', 'os_version'))
                            $table->string('os_version')->nullable();
                    });
                } catch (\Exception $e) {
                    logActivity("Unable to create mod_vmware_pw_linux_vm: {$e->getMessage()}");
                }

                $os = '';
                if (strchr(strtolower($cloneVmName), 'centos'))
                    $os = 'centos';
                else if (strchr(strtolower($cloneVmName), 'ubuntu'))
                    $os = 'ubuntu';
                else if (strchr(strtolower($cloneVmName), 'rhel'))
                    $os = 'rhel';
                else if (strchr(strtolower($cloneVmName), 'alma'))
                    $os = 'alma';
                else if (strchr(strtolower($cloneVmName), 'cloud'))
                    $os = 'cloud';
                else if (strchr(strtolower($cloneVmName), 'debian'))
                    $os = 'debian';
                if (!empty($os)) {
                    $raminbyte = $memoryMB * 1024 * 1024;
                    if ($memoryMB < 1024)
                        $ram = $memoryMB . ' MB';
                    else
                        $ram = ($memoryMB / 1024) . ' GB';

                    $oldPw = $this->ObjToArray->wgsvmwarePwencryption($cloneVmPassword);
                    $newRootPw = $this->ObjToArray->wgsvmwarePwencryption($rootPw);
                    $insertData = [
                        'vm_name' => $newVmname,
                        'old_password' => $oldPw,
                        'password' => $newRootPw,
                        'ip' => $ip,
                        'os' => $os,
                        'uid' => $uid,
                        'sid' => $serviceid,
                        'pid' => $params['pid'],
                        'ram' => $ram,
                        'hdd' => $totalDisk . ' GB',
                        'cpu' => $numCPUs,
                        'status' => '0',
                        'os_version' => $guetOsVersion,
                        'port' => $osport
                    ];
                }
                $serviceUserName = "root";
                if ($osType == 'Linux' || $osType == 'Others') {
                    $serviceUserName = "root";
                    if (strchr(strtolower($cloneVmName), 'ubuntu'))
                        $serviceUserName = "root";
                }
                if ($autoConfiguration && empty($dhcp)) {
                    if (!empty($insertData)) {
                        if (Capsule::table('mod_vmware_pw_linux_vm')->where('sid', $serviceid)->where('uid', $uid)->where('pid', $params['pid'])->count() == 0)
                            Capsule::table('mod_vmware_pw_linux_vm')->insert($insertData);
                        else
                            Capsule::table('mod_vmware_pw_linux_vm')->where('sid', $serviceid)->where('uid', $uid)->where('pid', $params['pid'])->update($insertData);
                    } else {
                        if (empty($reinstall)) {
                            if (file_exists(__DIR__ . '/send_vm_mail.php'))
                                require_once __DIR__ . '/send_vm_mail.php';
                        } else {
                            if (file_exists(__DIR__ . '/send_reinstall_email.php'))
                                require_once __DIR__ . '/send_reinstall_email.php';
                        }
                    }
                } else {
                    if (empty($reinstall)) {
                        if (file_exists(__DIR__ . '/send_vm_mail.php'))
                            require_once __DIR__ . '/send_vm_mail.php';
                    } else {
                        if (file_exists(__DIR__ . '/send_reinstall_email.php'))
                            require_once __DIR__ . '/send_reinstall_email.php';
                    }
                }

                return $status;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                return $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function cloneWindowsVm($cloneArr, $params, $customFields = null)
    {
        // public function cloneWindowsVm($cloneVmName, $cloneVmPassword, $newVmname, $ip, $netmask, $gateway, $dns, $macaddress = NULL, $serviceid, $userid, $firstname, $lastname, $serverName, $serverid, $companyname, $params, $hostsystem_name, $resource_pool_id, $datastore_id, $memoryMB, $numCPUs, $network_adapters, $disk_drives, $ipListArr = NULL, $_LANG = NULL, $dcObj = null, $autoConfiguration = null, $cpuMhz = null, $product_id = null, $dhcp = null, $fromtemplate = null, $osType = NULL, $reinstall = NULL, $guetOsVersion = NULL, $customFields = NULL, $adminId = NULL, $dbConn = NULL)
        $hostsystem_name = $cloneArr['hostsystem_name'];
        $reinstall = $cloneArr['reinstall'];
        $dcObj = $cloneArr['dcobj'];
        $networkIp = $cloneArr['networkIp'];
        $memoryMB = $cloneArr['memoryMB'];
        $numCPUs = $cloneArr['numCPUS'];
        $cpuMhz = $cloneArr['cpuMhz'];
        $datastore_id = $cloneArr['datastore_id'];
        $disk_drives = $cloneArr['disk_drives'];
        $cloneVmName = $cloneArr['templatename'];
        $newVmname = $cloneArr['newVmname'];
        $macaddress = trim($cloneArr['macaddress']);
        $network_adapters = $cloneArr['network_adapters'];
        $autoConfiguration = $cloneArr['autoConfiguration'];
        $dhcp = $cloneArr['dhcp'];
        $dns = $cloneArr['dns'];
        $ip = $networkIp;
        $gateway = $cloneArr['gateway'];
        $netmask = $cloneArr['netmask'];
        $serviceid = $params['serviceid'];
        $cloneVmPassword = $cloneArr['existingpw'];
        $ipListArr = $cloneArr['ipListArr'];
        $osType = $cloneArr['osType'];
        $guetOsVersion = $cloneArr['guetOsVersion'];
        $_LANG = $cloneArr['_LANG'];
        $companyname = $params['clientsdetails']['companyname'];
        $osport = $cloneArr['osport'];
        $product_id = $cloneArr['product_id'];
        try {
            $this->connect();

            $vncPw = $this->vmwareGenerateStrongPassword();

            $checkPort = $this->checkVmPortInDb($reinstall);

            $existWebSocifyPort = $checkPort['websockifyport'];

            $existVncPort = $checkPort['vncport'];

            if (empty($existVncPort))
                $vnc_port = 50000;
            else
                $vnc_port = $existVncPort + 1;

            if (empty($existWebSocifyPort))
                $webSocifyPort = 1000;
            else
                $webSocifyPort = $existWebSocifyPort + 1;

            $host = $this->vsphere->findManagedObjectByName('HostSystem', $hostsystem_name, array('name'));

            $host_obj = $host->parent->reference->_;

            $hostType = $host->parent->reference->type;
            $resourceRef = $this->get_host_resource_pool($host_obj, $hostType)->resourcePool->reference->_;

            $pool = $this->vsphere->findOneManagedObject('ResourcePool', $resourceRef, array('name'));

            $datacenter = $this->vsphere->findOneManagedObject('Datacenter', $dcObj, array('parent', 'name'));

            $folder = $datacenter->getVmFolder();
            $config = $this->reconfigureConfig($memoryMB, $numCPUs, $vncPw, $vnc_port, $webSocifyPort, $cpuMhz, $params, $networkIp);

            $datastore = $this->vsphere->findOneManagedObject('Datastore', $datastore_id, array('name'));

            // $cms = new VirtualMachineConfigSpec();

            $vmDevices = $this->vsphere->findManagedObjectByName('VirtualMachine', $cloneVmName, array('config.hardware.device'));
            $scsi_controller_key = $virtual_device_count = 1000;
            $scsi_controller_function = '';
            foreach ($vmDevices as $key => $dev) {
                if (is_array($dev)) {
                    foreach ($dev as $dKey => $device) {
                        if ($device instanceof VirtualLsiLogicController) {
                            $scsi_controller_function = 'VirtualLsiLogicController';
                        } elseif ($device instanceof VirtualLsiLogicSASController) {
                            $scsi_controller_function = 'VirtualLsiLogicSASController';
                        } elseif ($device instanceof ParaVirtualSCSIController) {
                            $scsi_controller_function = 'ParaVirtualSCSIController';
                        } elseif ($device instanceof VirtualBusLogicController) {
                            $scsi_controller_function = 'VirtualBusLogicController';
                        }

                        if ($device instanceof VirtualE1000) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualE1000';
                        } elseif ($device instanceof VirtualE1000e) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualE1000e';
                        } elseif ($device instanceof VirtualVmxnet3) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualVmxnet3';
                        } elseif ($device instanceof VirtualPcnet32) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualPcnet32';
                        } elseif ($device instanceof VirtualSriovEthernetCard) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'VirtualSriovEthernetCard';
                        } elseif ($device instanceof Virtualvlance) {
                            if (!empty($dhcp) && !empty($reinstall)) {
                                $existingMac = $device->macAddress = $existingMac = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : $device->macAddress;
                                $device->backing->port->portKey = '';
                                $device->addressType = "manual";
                                $existingDevice = $device;
                            }
                            $nickey = $device->key;
                            $networkName = ($device->backing->port) ? $device->backing->port->portgroupKey : $device->backing->deviceName;
                            $networkLabel = $device->deviceInfo->label;
                            $networksumary = $device->deviceInfo->summary;
                            $function = 'Virtualvlance';
                        }
                    }
                }
            }

            $virtual_devices = array();
            $virtual_devices[] = $this->create_scsi_controller_spec($scsi_controller_key, 0, $scsi_controller_function);
            $disk_number = 0;
            foreach ($disk_drives as $disk_info) {
                $virtual_devices[] = $this->create_disk_spec_edit($disk_info['capacity'], $datastore_id, $disk_number, $scsi_controller_key, $cloneVmName, $newVmname);
                ++$virtual_device_count;
                ++$disk_number;
            }

            if (!empty($dhcp) && !empty($reinstall)) {
                $virtual_devices[] = $this->create_mac_nic_spec($networkName, $networksumary, $nickey, $macaddress, $function, $networkLabel, $existingMac, $dhcp, $existingDevice);
                //$virtual_devices[] = $this->create_mac_nic_spec($networkName, $networksumary, $nickey, $existingMac, $function, $networkLabel, $existingMac, $dhcp, $existingDevice);
            } else {
                //            if ($autoConfiguration) {
                if (!empty($nickey) && !empty($networkName) && !empty($networksumary) && $networkLabel) {
                    $virtual_devices[] = $this->remove_mac_nic_spec($networkName, $networksumary, $nickey, $macaddress, $function, $networkLabel);
                }
                //            }
                if (!empty($macaddress)) {
                    $macAddress = $macaddress;
                } else {
                    $macAddress = '';
                }
                $nic_number = 0;
                foreach ($network_adapters as $network_adapter) {
                    $nic_name = 'nic_' . $nic_number;
                    if (strchr($network_adapter['network'], 'dvportgroup')) {
                        $network = $this->get_dv_port_group($network_adapter['network']);
                        $portGroup = $network->toReference()->_;
                        $dvs = $network->config->distributedVirtualSwitch->toReference()->_;
                        $vmDevices = $this->vsphere->findOneManagedObject('VmwareDistributedVirtualSwitch', $dvs, array('uuid'));
                        $switchUuid = $vmDevices->uuid;
                        $virtual_devices[] = $this->create_dvs_nic_spec($network->name, $nic_name, $macAddress, $function, $portGroup, $switchUuid);
                        ++$virtual_device_count;
                        ++$nic_number;
                    } else {
                        $network = $this->get_network($network_adapter['network']);
                        $virtual_devices[] = $this->create_nic_spec($network->name, $nic_name, $network_adapter['network'], $macAddress, $function);
                        ++$virtual_device_count;
                        ++$nic_number;
                    }
                }
            }

            $config->deviceChange = $virtual_devices;

            if (!empty($product_id))
                $productID = $product_id;
            else
                $productID = "";

            $cloneSpec = new VirtualMachineCloneSpec();
            $location = new VirtualMachineRelocateSpec();
            // if (!empty($fromtemplate)) {
            $location->pool = $pool->reference;
            $location->host = $host->reference;
            $diskLocators = new VirtualMachineRelocateSpecDiskLocator();
            $diskLocators->datastore = $datastore->reference;
            // $diskBackingInfo = new VirtualDeviceBackingInfo();
            // $diskLocators->diskBackingInfo = $diskBackingInfo;
            $diskLocators->diskId = '2000';
            // $diskLocators->diskMoveType = "VirtualMachineRelocateDiskMoveOptions.moveAllDiskBackingsAndConsolidate";
            // print_r($diskLocators);
            // die;
            $location->disk = $diskLocators;
            //  $mvOptn = new VirtualMachineRelocateDiskMoveOptions();
            // $location->diskMoveType = $mvOptn->createNewChildDiskBacking;
            // $location->DiskMoveType = "VirtualMachineRelocateDiskMoveOptions.CREATE_NEW_CHILD_DISK_BACKING.value()";
            // }
            $location->datastore = $datastore_id;
            $cloneSpec->location = $location;
            $cloneSpec->powerOn = true;
            $cloneSpec->template = false;
            $cloneSpec->config = $config;

            $customSpec = new CustomizationSpec();
            $winOptions = new CustomizationWinOptions();

            $winOptions->changeSID = true;
            $winOptions->deleteAccounts = false;
            //$winOptions->reboot = "reboot";

            $customSpec->options = $winOptions;
            $sprep = new CustomizationSysprep();

            $guiUnattended = new CustomizationGuiUnattended();
            $guiUnattended->autoLogon = true;
            $guiUnattended->autoLogonCount = 1;
            $guiUnattended->timeZone = 20;
            // $guiRunOnce = new CustomizationGuiRunOnce();
            // //$commandList = '"cmd /c WMIC computersystem where caption=\''current_pc_name'\' rename new_pc_name", "cmd /c shutdown /r"';
            // $guiRunOnce->commandList[0] = "cscript c:\windows\system32\slmgr.vbs -skms address.kmsserver11.com:1688";
            // $guiRunOnce->commandList[1] = "cscript c:\windows\system32\slmgr.vbs -ato";
            // $guiRunOnce->commandList[2] = "net user administrator /expires:never";
            // //$guiRunOnce->commandList = $commandList;
            // $sprep->GuiRunOnce = $guiRunOnce;

            $pass = new CustomizationPassword();

            $administratorPw = $this->vmwareGenerateStrongPassword(15);
            $pass->plainText = true;
            $pass->value = $administratorPw;
            $guiUnattended->password = $pass;

            $sprep->guiUnattended = $guiUnattended;

            $license = new CustomizationLicenseFilePrintData();
            $license->autoMode = "perServer";
            $license->autoUsers = 10;

            $sprep->licenseFilePrintData = $license;

            $custIdent = new CustomizationIdentification();
            $custIdent->joinWorkgroup = "WORKGROUP";
            $sprep->identification = $custIdent;

            $custUserData = new CustomizationUserData();
            $fixedName = new CustomizationFixedName();
            $fixedName->name = $this->WgsVmwareObj->vmwareClean($newVmname);

            if (strlen($fixedName->name) > 15)
                $fixedName->name = substr($fixedName->name, 0, 15);
            $fixedName->name = "desktop{$serviceid}"; //str_replace('.', '', $fixedName->name);

            $custUserData->productId = $productID;
            $custUserData->computerName = $fixedName;
            if (empty($companyname)) {
                $custUserData->fullName = preg_replace('/\s+/', '', $newVmname);
                $custUserData->fullName = "desktop{$serviceid}"; // str_replace('-', '', $newVmname);
                $custUserData->orgName = preg_replace('/\s+/', '', $newVmname);
            } else {
                $custUserData->fullName = preg_replace('/\s+/', '', $newVmname);
                $custUserData->fullName = "desktop{$serviceid}"; //str_replace('-', '', $newVmname);
                $custUserData->orgName = preg_replace('/\s+/', '', $companyname);
            }
            $sprep->userData = $custUserData;
            $customSpec->identity = $sprep;
            if ($autoConfiguration && empty($dhcp)) {
                $globalIPSettings = new CustomizationGlobalIPSettings();
                $globalIPSettings->dnsServerList = explode(',', $dns);

                $customSpec->globalIPSettings = $globalIPSettings;
                $fixedIp = new CustomizationFixedIp();
                $fixedIp->ipAddress = $ip;

                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = $fixedIp;
                $customizationIPSettings->gateway = array($gateway);
                $customizationIPSettings->subnetMask = $netmask;

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            } elseif (!empty($dhcp)) {
                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = new CustomizationDhcpIpGenerator();

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            } else {
                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = new CustomizationDhcpIpGenerator();

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            }
            $cloneSpec->customization = $customSpec;
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $cloneVmName, array('configStatus'));

            $arg = array('folder' => $folder->reference, 'name' => $newVmname, 'spec' => $cloneSpec);
  
            $ret = $vm_info->cloneVM_Task($arg);
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            /* if ($autoConfiguration && empty($dhcp))
              $administratorPw = $administratorPw;
              else
              $administratorPw = $cloneVmPassword; */
            if (!$reinstall) {
                logModuleCall("VMware", "Create windows VM from existing VM", $arg, $this->WgsVmwareObj->vmware_object_to_array($ret));
            } elseif ($reinstall) {
                logModuleCall("VMware", "Create windows VM from existing VM (Reinstall)", $arg, $this->WgsVmwareObj->vmware_object_to_array($ret));
            }

            if ($ret->info->state == 'success') {
                sleep(5);
                $vmInfo = $this->get_vm_hardware($newVmname);
                $existingMacAddress = "";
                foreach ($vmInfo as $key => $dev) {
                    if (is_array($dev)) {
                        foreach ($dev as $dKey => $device) {
                            if ($device->macAddress) {
                                $existingMacAddress = $device->macAddress;
                                break;
                            }
                        }
                    }
                }

                if (!empty($dhcp) && empty($reinstall)) {
                    try {
                        if (Capsule::table('mod_vmware_vm_ip')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count() > 0)
                            Capsule::table('mod_vmware_vm_ip')->update(['status' => '0']);
                        else
                            Capsule::table('mod_vmware_vm_ip')->insert(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'status' => '0']);
                    } catch (\Exception $ex) {
                        logActivity("Could not insert data in table mod_vmware_vm_ip. Error: {$ex->getMessage()}");
                    }
                }

                $status = $ret->info->state;

                $adminQuery = Capsule::table('tbladmins')->select('id')->first();
                $admin = $adminQuery->id;
                if (empty($reinstall)) {
                    $this->vmwareAssignIp($ipListArr, $newVmname, $dhcp, $params['serviceid']);
                }

                $params['vm_password'] = $cloneVmPassword = $administratorPw;

                $this->vmwareUpdateServiceField($serviceid, $newVmname, $ip, $params, $vnc_port, $vncPw, $admin, $webSocifyPort, $dcObj, $hostsystem_name, $osport, $reinstall, $osType, $guetOsVersion, $customFields, $dhcp, $existingMacAddress);   #update vnc detail , IP and vmware in service field
                $this->updateVmPortInDb($webSocifyPort, $vnc_port);

                $uid = $params['userid'];
                $new_vm_name = $newVmname;
                $networkIp = $ip;
                $serviceId = $serviceid;
                unset($ipListArr[0]);
                $serviceUserName = "Administrator";
                $vmPassword = $params['vm_password'];
                if ($memoryMB < 1024)
                    $ram = $memoryMB . ' MB';
                else
                    $ram = ($memoryMB / 1024) . ' GB';
                if (empty($reinstall)) {
                    $window = true;
                    if (file_exists(__DIR__ . '/send_vm_mail.php'))
                        require_once __DIR__ . '/send_vm_mail.php';
                } elseif ($reinstall) {
                    if (file_exists(__DIR__ . '/send_reinstall_email.php'))
                        require_once __DIR__ . '/send_reinstall_email.php';
                }
                return $status;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                return $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function create_disk_spec_edit($sizeGB, $datastore_id, $disk_number, $scsi_controller_key, $new_vm_name, $vmname = null)
    {
        $datastore = $this->vsphere->findOneManagedObject('Datastore', $datastore_id, array('name'));
        $datastore_name = $datastore->name;
        $devices = $this->vsphere->findManagedObjectByName('VirtualMachine', $new_vm_name, array('config.hardware.device'));
        $d_key = 2000 + $disk_number;
        $old = false;
        foreach ($devices as $key => $dev) {

            if (is_array($dev)) {
                foreach ($dev as $dKey => $device) {

                    if ($device instanceof VirtualDisk) {
                        if ($device->key == $d_key) {
                            $filename = $device->backing->fileName; //'[' . $datastore_name . '] ' . $vmname . '/' . $vmname . '.vmdk'; //
                            $oldCapacityInKB = $device->capacityInKB;
                            $existing_datastore_id = $device->backing->datastore->reference->_;
                            $contentid = $device->backing->contentId;
                            $uuid = $device->backing->uuid;
                            $storageIOAllocation = $device->storageIOAllocation;
                            $shares = $device->shares;
                            $old = true;
                        }
                    }
                }
            }
        }
        // if ($existing_datastore_id != $datastore_id)
        //     $old = false;
        $NewCapacityKB = $sizeGB * 1024 * 1024;
        if ($old) {
            if ($NewCapacityKB >= $oldCapacityInKB) {
                $disk_name = $new_vm_name . "_disk_" . $disk_number;

                $diskfileBacking = new VirtualDiskFlatVer2BackingInfo();
                $diskfileBacking->changeId = "";
                $diskfileBacking->fileName = $filename;
                $diskfileBacking->diskMode = "persistent";
                $diskfileBacking->eagerlyScrub = "";
                $diskfileBacking->split = false;
                $diskfileBacking->thinProvisioned = TRUE;
                $diskfileBacking->contentId = $contentid;
                $diskfileBacking->uuid = $uuid;
                $diskfileBacking->writeThrough = false;

                $desc = new Description();
                $desc->label = $disk_name;
                $desc->summary = "Disk " + $disk_number;

                $disk = new VirtualDisk();
                $disk->storageIOAllocation = $storageIOAllocation;
                $disk->shares = $shares;
                $disk->capacityInKB = $NewCapacityKB;
                $disk->controllerKey = $scsi_controller_key;
                $disk->unitNumber = $disk_number;
                $disk->backing = $diskfileBacking;
                $disk->key = 2000 + $disk_number;
                $disk->deviceInfo = $desc;

                $connectInfo = new VirtualDeviceConnectInfo();
                $connectInfo->allowGuestControl = 1;
                $connectInfo->startConnected = 1;
                $connectInfo->connected = 1;
                $disk->connectable = $connectInfo;

                $spec = new VirtualDeviceConfigSpec();
                $spec->operation = 'edit';
                $spec->device = $disk;
                return $spec;
            } else {
                $disk_name = $new_vm_name . "_disk_" . $disk_number;

                $diskfileBacking = new VirtualDiskFlatVer2BackingInfo();
                $diskfileBacking->fileName = $filename;
                $diskfileBacking->diskMode = "persistent";
                $diskfileBacking->thinProvisioned = TRUE;

                $desc = new Description();
                $desc->label = $disk_name;
                $desc->summary = "Disk " + $disk_number;

                $disk = new VirtualDisk();
                $disk->capacityInKB = $oldCapacityInKB;
                $disk->controllerKey = $scsi_controller_key;
                $disk->unitNumber = $disk_number;
                $disk->backing = $diskfileBacking;
                $disk->key = 2000 + $disk_number;
                $disk->deviceInfo = $desc;

                $spec = new VirtualDeviceConfigSpec();
                $spec->operation = 'edit';
                //$spec->fileOperation = 'replace';
                $spec->device = $disk;
                return $spec;
            }
        } else {
            //$capacityKB = $sizeGB * 1024 * 1024;
            $capacityKB = $NewCapacityKB - $oldCapacityInKB;
            $disk_name = $new_vm_name . "_disk_" . $disk_number;
            $filename = '[' . $datastore_name . ']';

            $diskfileBacking = new VirtualDiskFlatVer2BackingInfo();
            $diskfileBacking->fileName = '[' . $datastore_name . '] ' . $new_vm_name . '/' . $new_vm_name . '.vmdk';
            $diskfileBacking->diskMode = "persistent";
            $diskfileBacking->thinProvisioned = TRUE;

            $desc = new Description();
            $desc->label = $disk_name;
            $desc->summary = "Disk " + $disk_number;

            $disk = new VirtualDisk();
            $disk->capacityInKB = $capacityKB;
            $disk->controllerKey = $scsi_controller_key;
            $disk->unitNumber = $disk_number;
            $disk->backing = $diskfileBacking;
            $disk->key = 0 + $disk_number;
            $disk->deviceInfo = $desc;

            $spec = new VirtualDeviceConfigSpec();
            $spec->operation = 'add';
            $spec->fileOperation = 'create';
            $spec->device = $disk;
            return $spec;
        }
    }

    public function create_vm($vmData, $params, $customFields = null)
    {
        $hostsystem_name = $vmData['hostsystem_name'];
        $reinstall = $vmData['reinstall'];
        $dataceter_name = $vmData['dataceter_name'];
        $networkIp = $vmData['networkIp'];
        $memoryMB = $vmData['memoryMB'];
        $numCPUs = $vmData['numCPUS'];
        $cpuMhz = $vmData['cpuMhz'];
        $datastore_id = $vmData['datastore_id'];
        $disk_drives = $vmData['disk_drives'];
        $new_vm_name = $vmData['vmname'];
        $macaddress = trim($vmData['macaddress']);
        $network_adapters = $vmData['network_adapters'];
        $dhcp = $vmData['dhcp'];
        $dns = $vmData['dns'];
        $ip = $networkIp;
        $gateway = $vmData['gateway'];
        $netmask = $vmData['netmask'];
        $serviceId = $vmData['serviceid'];
        $ipListArr = $vmData['ipListArr'];
        $osType = $vmData['osType'];
        $guetOsVersion = $vmData['guetOsVersion'];
        $datastore_path = $vmData['datastore_path'];
        $isofile = $vmData['isofile'];
        $operating_system_id = $vmData['operating_system_id'];
        $_LANG = $vmData['_LANG'];
        try {
            $this->connect();

            $host = $this->vsphere->findManagedObjectByName('HostSystem', $hostsystem_name, array('name'));

            $host_obj = $host->parent->reference->_;

            $hostType = $host->parent->reference->type;
            $resourceRef = $this->get_host_resource_pool($host_obj, $hostType)->resourcePool->reference->_;

            $pool = $this->vsphere->findOneManagedObject('ResourcePool', $resourceRef, array('name'));

            $datacenter = $this->vsphere->findOneManagedObject('Datacenter', $dataceter_name, array('parent', 'name'));

            $folder = $datacenter->getVmFolder();
            $datastore = $this->vsphere->findOneManagedObject('Datastore', $datastore_id, array('name'));

            $cms = new VirtualMachineConfigSpec();
            if ($networkIp)
                $cms->annotation = "Created by WHMCS.\n User ID: {$params['userid']}\n Service ID: {$params['serviceid']}\n IP: {$networkIp}";
            else
                $cms->annotation = "Created by WHMCS.\n User ID: {$params['userid']}\n Service ID: {$params['serviceid']}";
            $cms->memoryMB = $memoryMB;
            $cms->name = $new_vm_name;
            $cms->memoryHotAddEnabled = TRUE;
            $cms->cpuHotAddEnabled = TRUE;
            $cms->cpuHotRemoveEnabled = TRUE;
            $cms->numCPUs = $numCPUs;
            $cpuResInfo = new ResourceAllocationInfo();
            $cpuResInfo->limit = $cpuMhz . 'MHz';
            if (!empty($cpuMhz))
                $cms->cpuAllocation = $cpuResInfo;

            $memResInfo = new ResourceAllocationInfo();
            $memResInfo->limit = $memoryMB . 'MB';
            $cms->memoryAllocation = $memResInfo;

            $cms->guestId = $operating_system_id;
            $cms->spec = 'cdrom0';
            $vm_files_spec = new VirtualMachineFileInfo();
            $vm_files_spec->vmPathName = '[' . $datastore->name . ']'; //'[' . $datastore_name . ']'; //
            $cms->files = $vm_files_spec;

            $vncPw = $this->vmwareGenerateStrongPassword();

            $checkPort = $this->checkVmPortInDb($reinstall);

            $existWebSocifyPort = $checkPort['websockifyport'];

            $existVncPort = $checkPort['vncport'];

            if (empty($existVncPort))
                $vnc_port = 50000;
            else
                $vnc_port = $existVncPort + 1;

            if (empty($existWebSocifyPort))
                $webSocifyPort = 1000;
            else
                $webSocifyPort = $existWebSocifyPort + 1;

            $vncEnabled = new OptionValue();
            $vncEnabled->key = "Remotedisplay.vnc.enabled";
            $vncEnabled->value = new SoapVar("FALSE", XSD_STRING, 'xsd:string');

            $vncPort = new OptionValue();
            $vncPort->key = "Remotedisplay.vnc.port";
            $vncPort->value = new SoapVar($vnc_port, XSD_STRING, 'xsd:string');

            $vncPwd = new OptionValue();
            $vncPwd->key = "Remotedisplay.vnc.password";
            $vncPwd->value = new SoapVar($vncPw, XSD_STRING, 'xsd:string');

            $vncWebscoketEnabled = new OptionValue();
            $vncWebscoketEnabled->key = "RemoteDisplay.vnc.WebSocket.enabled";
            $vncWebscoketEnabled->value = new SoapVar("TRUE", XSD_STRING, 'xsd:string');

            $vncWebscoketPort = new OptionValue();
            $vncWebscoketPort->key = "RemoteDisplay.vnc.WebSocket.port";
            $vncWebscoketPort->value = new SoapVar($webSocifyPort, XSD_STRING, 'xsd:string');

            $extraConfig = array($vncEnabled, $vncPort, $vncPwd, $vncWebscoketEnabled, $vncWebscoketPort);
            $cms->extraConfig = $extraConfig;

            $virtual_devices = array();
            $virtual_device_count = 1000;
            $scsi_controller_key = $virtual_device_count;
            ++$virtual_device_count;
            $virtual_devices[] = $this->create_scsi_controller_spec($scsi_controller_key, 0, "VirtualLsiLogicController");

            $disk_number = 0;
            foreach ($disk_drives as $disk_info) {
                $virtual_devices[] = $this->create_disk_spec($disk_info['capacity'], $datastore->name, $disk_number, $scsi_controller_key, $new_vm_name);
                ++$virtual_device_count;
                ++$disk_number;
            }
            //  $virtual_devices[] = $this->cdroms($datastore_path, $isofile);
            $virtual_devices[] = $this->cdroms($datastore->name, $isofile);

            ++$virtual_device_count;
            ++$disk_number;

            if (!empty($dhcp) && !empty($reinstall))
                $macAddress = $macaddress = !empty($params['customfields']['mac_address']) ? $params['customfields']['mac_address'] : "";
            else {
                if (!empty($macaddress)) {
                    $macAddress = $macaddress;
                } else {
                    $macAddress = '';
                }
            }
            $nic_number = 0;
            foreach ($network_adapters as $network_adapter) {
                $nic_name = 'nic_' . $nic_number;
                if (strchr($network_adapter['network'], 'dvportgroup')) {
                    $network = $this->get_dv_port_group($network_adapter['network']);
                    $portGroup = $network->toReference()->_;
                    $dvs = $network->config->distributedVirtualSwitch->toReference()->_;
                    $vmDevices = $this->vsphere->findOneManagedObject('VmwareDistributedVirtualSwitch', $dvs, array('uuid'));
                    $switchUuid = $vmDevices->uuid;
                    $function = 'VirtualE1000';
                    $virtual_devices[] = $this->create_dvs_nic_spec($network->name, $nic_name, $macAddress, $function, $portGroup, $switchUuid);
                    ++$virtual_device_count;
                    ++$nic_number;
                } else {
                    $network = $this->get_network($network_adapter['network']);
                    $virtual_devices[] = $this->create_nic_spec($network->name, $nic_name, $network_adapter['network'], $macAddress);
                    ++$virtual_device_count;
                    ++$nic_number;
                }
            }
            $cms->deviceChange = $virtual_devices;

            $args = array('config' => $cms, 'pool' => $pool->reference, 'host' => $host->reference);
            $ret = $folder->CreateVM_Task($args);

            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if (empty($reinstall)) {
                logModuleCall("VMware", "Create VM", $args, $this->WgsVmwareObj->vmware_object_to_array($ret));
            } elseif ($reinstall) {
                logModuleCall("VMware", "Create VM (Reinstall)", $args, $this->WgsVmwareObj->vmware_object_to_array($ret));
            }
            if ($ret->info->state == 'success') {
                sleep(5);
                $vmInfo = $this->get_vm_hardware($new_vm_name);
                $existingMacAddress = "";
                foreach ($vmInfo as $key => $dev) {
                    if (is_array($dev)) {
                        foreach ($dev as $dKey => $device) {
                            if ($device->macAddress) {
                                $existingMacAddress = $device->macAddress;
                                break;
                            }
                        }
                    }
                }

                if (!empty($dhcp) && empty($reinstall)) {
                    try {
                        if (Capsule::table('mod_vmware_vm_ip')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count() > 0)
                            Capsule::table('mod_vmware_vm_ip')->update(['status' => '0']);
                        else
                            Capsule::table('mod_vmware_vm_ip')->insert(['uid' => $params['userid'], 'sid' => $params['serviceid'], 'status' => '0']);
                    } catch (\Exception $ex) {
                        logActivity("Could not insert data in table mod_vmware_vm_ip. Error: {$ex->getMessage()}");
                    }
                }
                $status = 'success';
                $adminQuery = Capsule::table('tbladmins')->select('id')->get();
                $admin = $adminQuery[0]->id;
                if (empty($reinstall)) {
                    $this->vmwareAssignIp($ipListArr, $new_vm_name, $dhcp, $params['serviceid']);
                }

                $this->vmwareUpdateServiceField($serviceId, $new_vm_name, $networkIp, $params, $vnc_port, $vncPw, $admin, $webSocifyPort, $dataceter_name, $hostsystem_name, $reinstall, $osType, $guetOsVersion, $customFields, $dhcp, $existingMacAddress);

                $this->updateVmPortInDb($webSocifyPort, $vnc_port);

                unset($ipListArr[0]);
                $serviceUserName = "Administrator";
                if ($osType == 'Linux' || $osType == 'Others') {
                    $serviceUserName = "root";
                }
                if (empty($reinstall)) {
                    if (file_exists(__DIR__ . '/send_vm_mail.php'))
                        require_once __DIR__ . '/send_vm_mail.php';
                } else {
                    if (file_exists(__DIR__ . '/send_reinstall_email.php'))
                        require_once __DIR__ . '/send_reinstall_email.php';
                }
                return $status;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);

                return $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exeption $e) {
            return $e->getMessage();
        }
    }

    public function reconfigure_vm($newVmname, $datastore_id, $memoryMB, $numCPUs, $diskSize, $serviceId, $ipListArr = NULL, $cpuMhz = NULL, $dhcp = null)
    {
        try {
            $this->connect();

            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $newVmname, array('summary'));
            $vmDevices = $this->vsphere->findManagedObjectByName('VirtualMachine', $newVmname, array('config.hardware.device'));
            $scsi_controller_key = $virtual_device_count = '';
            foreach ($vmDevices as $key => $dev) {
                if (is_array($dev)) {
                   foreach ($dev as $dKey => $device) {
                        if ( 'VirtualDisk' == get_class($device)) {
                            if ($device instanceof VirtualLsiLogicController) {
                                $virtual_device_count = $device->key;
                                $scsi_controller_key = $device->controllerKey;
                            } elseif ($device instanceof VirtualLsiLogicSASController) {
                                $virtual_device_count = $device->key;
                                $scsi_controller_key = $device->controllerKey;
                            } elseif ($device instanceof ParaVirtualSCSIController) {
                                $virtual_device_count = $device->key;
                                $scsi_controller_key  = $device->controllerKey;
                            } elseif ($device instanceof VirtualBusLogicController) {
                                $virtual_device_count = $device->key;
                                $scsi_controller_key = $device->controllerKey;
                            } else {
                                $virtual_device_count = $device->key;
                                $scsi_controller_key  = $device->controllerKey;
                            }
                        }
                    }    
                }
            }


            $spec = new VirtualMachineConfigSpec();

            $spec->memoryMB = $memoryMB;
            $spec->memoryHotAddEnabled = TRUE;
            $spec->cpuHotAddEnabled = TRUE;
            $spec->cpuHotRemoveEnabled = TRUE;
            $spec->numCPUs = $numCPUs;

            $cpuResInfo = new ResourceAllocationInfo();
            $cpuResInfo->limit = $cpuMhz . 'MHz';

            if (!empty($cpuMhz))
                $spec->cpuAllocation = $cpuResInfo;

            $memResInfo = new ResourceAllocationInfo();
            $memResInfo->limit = $memoryMB . 'MB';
            $spec->memoryAllocation = $memResInfo;

            $virtual_devices = array();
            // $virtual_device_count = 1000;
            // $scsi_controller_key = $virtual_device_count;
            $disk_number = 0;
            // print_r($disk_drives);
            // foreach ($disk_drives as $disk_info) {
            //     $virtual_devices[] = $this->create_disk_spec_edit($disk_info['capacity'], $datastore->name, $disk_number, $scsi_controller_key, $newVmname, $newVmname);
            //     ++$virtual_device_count;
            //     ++$disk_number;
            // }
            $virtual_devices[] = $this->create_disk_spec_edit($diskSize, $datastore_id, $disk_number, $scsi_controller_key, $newVmname, $newVmname);
            foreach ($virtual_devices as $key => $devices) {
                if (empty($devices)) {
                    unset($virtual_devices[$key]);
                }
            }
            $spec->deviceChange = $virtual_devices;

            $return = $vm_info->ReconfigVM_Task(array('spec' => $spec));
            while (in_array($return->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            logModuleCall("VMware", "Upgrade package", array('name' => $newVmname, 'spec' => $spec), $this->WgsVmwareObj->vmware_object_to_array($return));

            if ($return->info->state == 'success') {
                $status = 'success';

                $this->vmwareAssignIp($ipListArr, $newVmname, $dhcp, $serviceId, true);

                return $status;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($return);

                return $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function reconfigureExistingVm($vm_name, $serviceid, $pid, $hostname = null, $dc = null)
    {
        try {
            $vncFieldId = $this->vmwareGetCfId($pid, 'vnc_detail');
            $hostFieldId = $this->vmwareGetCfId($pid, 'hostname_dc');

            $adminQuery = Capsule::table('tbladmins')->select('id')->get();
            $admin = $adminQuery[0]->id;

            $command = "updateclientproduct";
            $adminuser = $admin;
            $values["serviceid"] = $serviceid;
            $values["status"] = 'Active';
            if (!empty($hostname)) {
                //                $values["customfields"] = base64_encode(serialize(array($vncFieldId => $vnc_port . ' ' . $vncPw . ' ' . $webSocifyPort, $hostFieldId => $hostname . '&&' . $dc)));
                $values["customfields"] = base64_encode(serialize(array($hostFieldId => $hostname . '&&' . $dc)));
                $results = localAPI($command, $values, $adminuser);
            } else {
                //                $values["customfields"] = base64_encode(serialize(array($vncFieldId => $vnc_port . ' ' . $vncPw . ' ' . $webSocifyPort)));
                //                $results = localAPI($command, $values, $adminuser);
            }
            $status = 'success';
            return $status;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getDevices($new_vm_name)
    {
        try {
            $this->connect();
            return $this->vsphere->findManagedObjectByName('VirtualMachine', $new_vm_name, array('config.hardware.device'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function createUser($username, $password)
    {
        try {
            $this->connect();
            echo '<pre>';
            print_r($this->vsphere->getServiceContent());
            die;
            // $User = new stdClass();
            // $User->id = $username;
            // $User->password = $password;
            $rootRef = $this->vsphere->getRootFolder();
            $HostLocalAccountManager = $this->vsphere->getAccountManager();
            print_r($HostLocalAccountManager);
            $hostAccountSpec = new HostAccountSpec();
            $hostAccountSpec->id = $username;
            $hostAccountSpec->password = $password;
            $hostAccountSpec->description = "Create by WHMCS VMware Module";
            $response = $rootRef->CreateUser(array('user' => $hostAccountSpec));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addPermission($_Entity, $_roleName, $_username)
    {
        try {
            $this->connect();
            $hostAuthorizationManager = $this->vsphere->getAuthorizationManager();
            $Permission = new Permission();
            $Permission->roleId = '-69475883';
            $Permission->propagate = true;
            $Permission->group = false;
            $Permission->principal = $_username;
            $ret = $hostAuthorizationManager->setEntityPermissions(array('entity' => $_Entity, 'permission' => $Permission));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function terminateUser($username)
    {
        $this->connect();
        $HostLocalAccountManager = $this->vsphere->getAccountManager();
        $HostLocalAccountManager->RemoveUser(array('userName' => $username));
    }

    public function getVmDisks($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('summary'));
            $vmware = $vm_info->getGuest();
            return $vmware->disk;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function create_disk_spec($sizeGB, $datastore_name, $disk_number, $scsi_controller_key, $new_vm_name)
    {
        $capacityKB = $sizeGB * 1024 * 1024;
        $disk_name = $new_vm_name . "_disk_" . $disk_number;
        $filename = '[' . $datastore_name . ']';

        $diskfileBacking = new VirtualDiskFlatVer2BackingInfo();
        $diskfileBacking->fileName = $filename;
        $diskfileBacking->diskMode = "persistent";
        $diskfileBacking->thinProvisioned = TRUE;

        $desc = new Description();
        $desc->label = $disk_name;
        $desc->summary = "Disk " + $disk_number;

        $disk = new VirtualDisk();
        $disk->capacityInKB = $capacityKB;
        $disk->controllerKey = $scsi_controller_key;
        $disk->unitNumber = $disk_number;
        $disk->backing = $diskfileBacking;
        $disk->key = 0;
        $disk->deviceInfo = $desc;

        $spec = new VirtualDeviceConfigSpec();
        $spec->operation = 'add';
        $spec->fileOperation = 'create';
        $spec->device = $disk;
        return $spec;
    }

    private function cdroms($datastore_name, $isofile)
    {
        $CdDeviceBacking = new VirtualCdromIsoBackingInfo();
        $datastore = $this->vsphere->findManagedObjectByName('Datastore', $datastore_name, array('name'));

        $CdDeviceBacking->datastore = $datastore->name;
        $CdDeviceBacking->fileName = '[' . $datastore->name . '] iso/' . $isofile;
        $Cdrom = new VirtualCdrom();
        $Cdrom->backing = $CdDeviceBacking;
        $Cdrom->key = 20;
        $Cdrom->controllerKey = 201;
        $Cdrom->controllerKeySpecified = true;
        $Cdrom->unitNumberSpecified = true;
        $Cdrom->unitNumber = 0;
        $CdSpec = new VirtualDeviceConfigSpec();
        $CdSpec->operation = 'add';
        $CdSpec->operationSpecified = true;
        $CdSpec->device = $Cdrom;
        return $CdSpec;
    }

    private function add_device_to_vm($vm, $device)
    {

        $cms = new VirtualMachineConfigSpec();
        $cms->deviceChange = $device;
        $args = array('spec' => $cms);

        return $vm->ReconfigVM_Task($args);
    }

    private function create_scsi_controller_spec($device_id, $bus_id = 0, $function = null)
    {
        $scsiSpec = new VirtualDeviceConfigSpec();
        $scsiSpec->operation = 'add';
        $scsiCtrl = new $function();
        $scsiCtrl->key = $device_id;
        $scsiCtrl->busNumber = $bus_id;
        $scsiCtrl->sharedBus = 'noSharing';
        $scsiSpec->device = $scsiCtrl;
        return $scsiSpec;
    }

    private function create_dvs_nic_spec($network_name, $nic_name, $macAddress = NULL, $function = null, $portgroupKey = null, $switchUuid = null)
    {

        $nicSpec = new VirtualDeviceConfigSpec();
        $nicSpec->operation = 'add';

        if ($function == 'VirtualE1000') {
            $nic = new VirtualE1000();
        } elseif ($function == 'VirtualE1000e') {
            $nic = new VirtualE1000e();
        } elseif ($function == 'VirtualVmxnet3') {
            $nic = new VirtualVmxnet3();
        } elseif ($function == 'VirtualPcnet32') {
            $nic = new VirtualPCNet32();
        } elseif ($function == 'VirtualSriovEthernetCard') {
            $nic = new VirtualSriovEthernetCard();
        } elseif ($function == 'Virtualvlance') {
            $nic = new VirtualPCNet32();
        } else {
            $nic = new VirtualVmxnet3();
        }

        $info = new Description();
        $info->label = $nic_name;
        $info->summary = $network_name;
        $nic->deviceInfo = $info;

        if (!empty($macAddress)) {
            $nic->addressType = "manual";
            $nic->macAddress = $macAddress;
        } else {
            $nic->addressType = "assigned";
        }

        $nicBacking = new VirtualEthernetCardDistributedVirtualPortBackingInfo();
        $portConn = new DistributedVirtualSwitchPortConnection();
        $portConn->switchUuid = $switchUuid;
        $portConn->portgroupKey = $portgroupKey;
        $portConn->portKey = '';
        //$portConn->connectionCookie = '';
        $nicBacking->port = $portConn;
        $nic->backing = $nicBacking;
        $connectInfo = new VirtualDeviceConnectInfo();
        $connectInfo->allowGuestControl = 1;
        $connectInfo->startConnected = 1;
        $connectInfo->connected = 1;
        $connectInfo->status = 'ok';
        $nic->connectable = $connectInfo;
        $nic->key = 0;

        $nicSpec->device = $nic;
        return $nicSpec;
    }

    private function create_nic_spec($network_name, $nic_name, $networkId, $macAddress = NULL, $function = null)
    {
        $nicSpec = new VirtualDeviceConfigSpec();
        $nicSpec->operation = 'add';

        if ($function == 'VirtualE1000') {
            $nic = new VirtualE1000();
        } elseif ($function == 'VirtualE1000e') {
            $nic = new VirtualE1000e();
        } elseif ($function == 'VirtualVmxnet3') {
            $nic = new VirtualVmxnet3();
        } elseif ($function == 'VirtualPcnet32') {
            $nic = new VirtualPCNet32();
        } elseif ($function == 'VirtualSriovEthernetCard') {
            $nic = new VirtualSriovEthernetCard();
        } elseif ($function == 'Virtualvlance') {
            $nic = new VirtualPCNet32();
        } else
            $nic = new VirtualVmxnet3();
        $nicBacking = new VirtualEthernetCardNetworkBackingInfo();

        $nicBacking->deviceName = $network_name;
        $nicBacking->network = $networkId;

        $info = new Description();
        $info->label = $nic_name;
        $info->summary = $network_name;
        $nic->deviceInfo = $info;

        if (!empty($macAddress)) {
            $nic->addressType = "manual";
            $nic->macAddress = $macAddress;
        } else {
            $nic->addressType = "generated";
        }
        $nic->backing = $nicBacking;
        $connectInfo = new VirtualDeviceConnectInfo();
        $connectInfo->allowGuestControl = 1;
        $connectInfo->startConnected = true;
        // $connectInfo->migrateConnect = "connect";
        $connectInfo->connected = true;
        $connectInfo->status = 'ok';
        $nic->connectable = $connectInfo;
        $nic->key = 0;

        $nicSpec->device = $nic;
        return $nicSpec;
    }

    private function create_mac_nic_spec($network_name = null, $nic_name, $key, $macaddress = NULL, $function = null, $networkLabel = null, $existingMac = null, $dhcp = null, $existingDevice = null)
    {
        $nicSpec = new VirtualDeviceConfigSpec();
        $nicSpec->operation = 'edit';

        if ($function == 'VirtualE1000') {
            $nic = new VirtualE1000();
        } elseif ($function == 'VirtualE1000e') {
            $nic = new VirtualE1000e();
        } elseif ($function == 'VirtualVmxnet3') {
            $nic = new VirtualVmxnet3();
        } elseif ($function == 'VirtualPcnet32') {
            $nic = new VirtualPCNet32();
        } elseif ($function == 'VirtualSriovEthernetCard') {
            $nic = new VirtualSriovEthernetCard();
        } elseif ($function == 'Virtualvlance') {
            $nic = new VirtualPCNet32();
        }
        $nicBacking = new VirtualEthernetCardNetworkBackingInfo();
        $nicBacking->deviceName = $network_name;

        $info = new Description();
        $info->label = $networkLabel;
        $info->summary = $nic_name;
        $nic->deviceInfo = $info;

        if (!empty($macaddress)) {
            $nic->addressType = "manual";
            $nic->macAddress = $macaddress;
        } else {
            $nic->addressType = "assigned";
        }

        if ($dhcp && $existingMac) {
            $nic->macAddress = $existingMac;
        }

        $nic->backing = $nicBacking;
        $connectInfo = new VirtualDeviceConnectInfo();
        $connectInfo->allowGuestControl = true;
        $connectInfo->startConnected = true;
        $connectInfo->connected = true;
        $connectInfo->status = 'ok';
        $nic->connectable = $connectInfo;
        $nic->key = $key;

        $nicSpec->device = $existingDevice;
        return $nicSpec;
    }

    private function remove_mac_nic_spec($network_name = null, $nic_name, $key, $macaddress = NULL, $function = null, $networkLabel = null)
    {
        $nicSpec = new VirtualDeviceConfigSpec();
        $nicSpec->operation = 'remove';

        if ($function == 'VirtualE1000') {
            $nic = new VirtualE1000();
        } elseif ($function == 'VirtualE1000e') {
            $nic = new VirtualE1000e();
        } elseif ($function == 'VirtualVmxnet3') {
            $nic = new VirtualVmxnet3();
        } elseif ($function == 'VirtualPcnet32') {
            $nic = new VirtualPCNet32();
        } elseif ($function == 'VirtualSriovEthernetCard') {
            $nic = new VirtualSriovEthernetCard();
        } elseif ($function == 'Virtualvlance') {
            $nic = new VirtualPCNet32();
        }
        $nicBacking = new VirtualEthernetCardNetworkBackingInfo();

        $nicBacking->deviceName = $network_name;

        $info = new Description();
        $info->label = $networkLabel;
        $info->summary = $nic_name;
        $nic->deviceInfo = $info;
        $nic->backing = $nicBacking;
        $nic->key = $key;

        $nicSpec->device = $nic;
        return $nicSpec;
    }

    public function get_vm_storage_info($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('storage'));
        return $vm_info;
    }

    public function get_vm_info($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('summary'));
        return $vm_info;
    }

    public function get_vm_hardware($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('config.hardware.device'));
        return $vm_info;
    }

    public function get_vm_guest($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('guest'));
        return $vm_info;
    }

    public function get_vm_mac_addresses($vm_name, $first_only = FALSE)
    {
        $mac_addresses = array();
        $vm_info = $this->get_vm_info($vm_name);
        $vm_hardware = $vm_info->RetrieveProperties($vm_name, 'VirtualMachine', 'config.hardware');
        echo '<pre>';
        print_r($vm_hardware);
        die();
        if (count($vm_hardware->device) > 0) {
            foreach ($vm_hardware->device as $device) {
                if (!empty($device->macAddress)) {
                    array_push($mac_addresses, $device->macAddress);
                    if ($first_only) {
                        break;
                    }
                }
            }
        }

        return $mac_addresses;
    }

    public function getAcquireCloneTicket()
    {
        $this->connect();
        $SessionManager = $this->vsphere->getSessionManager();
        return $SessionManager->AcquireCloneTicket();
    }

    function WGSgetCertificateInfo($server, $port = 443, $timeout = false)
    {
        define('CERTVIEWER_VERSION', '1.20 20130317');
        $context = stream_context_create(array(
            'ssl' => array('capture_peer_cert' => true)
        ));

        if ($timeout) {
        } else {
            $timeout = ini_get('default_socket_timeout');
        }

        $errno = $errstr = 0;
        $fp = stream_socket_client('ssl://' . $server . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        $params = stream_context_get_params($fp);
        fclose($fp);
        $cp = $params['options']['ssl']['peer_certificate'];
        $cert = '';
        openssl_x509_export($cp, $cert);
        $certArray = openssl_x509_parse($cp);
        openssl_x509_free($cp);
        $certArray1 = array();
        $certArray1['x-server-port'] = $server . ':' . $port;
        $certArray1['x-server'] = $server;
        $certArray1['x-port'] = $port;
        $certArray1['x-retrieval-time'] = array('utc' => gmdate('YmdHis\\Z'), 'unix' => gmdate('U'));
        $certArray1['x-mysimplecertviewer-version'] = CERTVIEWER_VERSION;
        $decCert = preg_replace('/\\-+(BEGIN|END) CERTIFICATE\\-+/', '', $cert);

        if (strpos('BEGIN CERTIFICATE') !== false) {
            $decCert = str_replace('-----BEGIN CERTIFICATE-----', '', $decCert);
            $decCert = str_replace('-----END CERTIFICATE-----', '', $decCert);
        }

        $decCert = str_replace(array("\n\r", "\n", "\r"), '', trim($decCert));
        $decCert = base64_decode($decCert);
        $certArray1['x-fingerprints'] = array('sha1' => sha1($decCert), 'md5' => md5($decCert), 'sha256' => hash('sha256', $decCert));
        $certArray['extensions']['x-subjectAltName'] = explode(',', $certArray['extensions']['subjectAltName']);
        $certArray['x-certificate-base64'] = $cert;
        return $certArray1 + $certArray;
    }

    public function createTicketToken($vm_name,$old = null)
    {
        $this->connect();
        if($old){
            $SessionManager = $this->vsphere->getSessionManager();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            return $vm_info->AcquireTicket(array('ticketType' => 'mks'));
        }else{
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            return $vm_info->AcquireTicket(array('ticketType' => 'webmks'));
        }
        //        $SessionManager = $this->vsphere->getSessionManager();
        //        return array('token' => $SessionManager->acquireMksTicket(), 'mksticket' => $vm_info->AcquireTicket(array('ticketType' => 'mks'))->ticket);
    }

    public function vm_pause($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
        $ret = $vm_info->PowerOffVM_Task();
        while (in_array($ret->info->state, array('running', 'queued'))) {
            sleep(1);
        }
        return $ret;
    }

    public function vm_unpause($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
        $ret = $vm_info->PowerOnVM_Task();
        while (in_array($ret->info->state, array('running', 'queued'))) {
            sleep(1);
        }
        return $ret;
    }

    public function vm_installvmwaretool($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
        $ret = $vm_info->UpgradeTools_Task();
        while (in_array($ret->info->state, array('running', 'queued'))) {
            sleep(1);
        }
        return $ret;
    }

    public function vm_upgradevmwaretool($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->UpgradeTools_Task();

            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }

            logModuleCall("VMware", "upgrade VM tool", array('name' => $vm_name), $this->WgsVmwareObj->vmware_object_to_array($ret));

            $ret = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function vm_mount($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->MountToolsInstaller();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            $state = 'success';
        } catch (Exception $ex) {
            $state = $ex->getMessage();
        }

        return array('state' => $state);
    }

    public function vm_unmount($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->UnmountToolsInstaller();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            $state = 'success';
        } catch (Exception $ex) {
            $state = $ex->getMessage();
        }

        return array('state' => $state);
    }

    public function vm_power_on($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->PowerOnVM_Task();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function vm_power_off($vm_name, $reinstall = null)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->PowerOffVM_Task();
           // $status = $this->getState($ret->toReference()->_);

            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $state = $ex->getMessage();
        }
        logModuleCall("VMware", "Power Off", array('name' => $vm_name), $state);
        return array('state' => $state, 'obj' => $ret);

    }

    public function getState($taskID){
        $state = $this->get_vm_recent_task_info($taskID)->info->state;
        if(in_array($state, array('running', 'queued'))){
            $state = $this->getState($taskID);
        }
        return $state;
    }

    public function vm_shut_down($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->ShutdownGuest();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $state = $ex->getMessage();
        }
        logModuleCall("VMware", "Shut Down", array('name' => $vm_name), $state);
        return array('state' => $state, 'obj' => $ret);
    }

    public function vm_destry($vm_name, $reinstall = null)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));

            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->Destroy_Task();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                if (!empty($reinstall)) {
                    $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
                } else {
                    $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
                }
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function vm_suspend($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->SuspendVM_Task();

            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function get_all_existing_vms()
    {
        $this->connect();
        $all_vms = $this->vsphere->findAllManagedObjects('VirtualMachine', array('name', 'summary')); //configStatus
        return $all_vms;
    }

    public function get_all_vms()
    {
        $this->connect();
        $all_vms = $this->vsphere->findAllManagedObjects('VirtualMachine', array('parent', 'name', 'runtime')); //configStatus
        $vm_list = array();
        foreach ($all_vms as $key => $vm) {

            $vm_info = array();
            $vm_info['object'] = $vm;
            $vm_info['hostname'] = $vm->name;
            $vm_info['parent'] = $vm->parent;
            $vm_info['hardware'] = $vm->getHardware();
            $vm_info['guest_info'] = $vm->getGuestInfo();
            $vm_list[$vm_info['hostname']] = $vm_info;
        }
        return $vm_list;
    }

    public function GetNetworkStats($vm_name)
    {
        $this->connect();

        $strEndTime = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime(date('Y-m-d H:i:s'))));
        $strStartTime = date('Y-m-d 00:00:01', strtotime('-1 day', strtotime(date('Y-m-d H:i:s'))));
        $intNetworkCardID = '';
        $vm_info = $this->get_vm_info($vm_name);
        print_r($vm_info);
        exit();
    }

    public function vm_reboot($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            $ret = $vm_info->RebootGuest();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function resetVmPw($dataArr)
    {
        $newVmname = $dataArr['vmname'];
        $pw = $dataArr['pw'];
        $serviceid = $dataArr['serviceid'];
        $companyname = $dataArr['companyname'];
        $macaddress = trim($dataArr['macaddress']);
        $autoConfiguration = $dataArr['autoConfiguration'];
        $dhcp = $dataArr['dhcp'];
        $dns = $dataArr['dns'];
        $ip = $dataArr['networkIp'];
        $gateway = $dataArr['gateway'];
        $netmask = $dataArr['netmask'];
        try {
            $this->connect();

            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $newVmname, array('summary'));
            
            $customSpec = new CustomizationSpec();
            $winOptions = new CustomizationWinOptions();

            $winOptions->changeSID = true;
            $winOptions->deleteAccounts = false;
            //$winOptions->reboot = "reboot";

            $customSpec->options = $winOptions;
            $sprep = new CustomizationSysprep();

            $guiUnattended = new CustomizationGuiUnattended();
            $guiUnattended->autoLogon = false;
            $guiUnattended->autoLogonCount = 0;
            $guiUnattended->timeZone = 20;

            $pass = new CustomizationPassword();

            $administratorPw = $pw;
            $pass->plainText = true;
            $pass->value = $administratorPw;
            $guiUnattended->password = $pass;

            $sprep->guiUnattended = $guiUnattended;
			
			$license = new CustomizationLicenseFilePrintData();
            $license->autoMode = "perServer";
            $license->autoUsers = 10;

            $sprep->licenseFilePrintData = $license;

            $custIdent = new CustomizationIdentification();
            $custIdent->joinWorkgroup = "WORKGROUP";
            $sprep->identification = $custIdent;

            $custUserData = new CustomizationUserData();
            $fixedName = new CustomizationFixedName();
            $fixedName->name = $this->WgsVmwareObj->vmwareClean($newVmname);

            if (strlen($fixedName->name) > 15)
                $fixedName->name = substr($fixedName->name, 0, 15);
                $fixedName->name = "desktop{$serviceid}"; //str_replace('.', '', $fixedName->name);

            $custUserData->productId = "";
            $custUserData->computerName = $fixedName;
            if (empty($companyname)) {
                $custUserData->fullName = preg_replace('/\s+/', '', $newVmname);
                $custUserData->fullName = "desktop{$serviceid}"; // str_replace('-', '', $newVmname);
                $custUserData->orgName = preg_replace('/\s+/', '', $newVmname);
            } else {
                $custUserData->fullName = preg_replace('/\s+/', '', $newVmname);
                $custUserData->fullName = "desktop{$serviceid}"; //str_replace('-', '', $newVmname);
                $custUserData->orgName = preg_replace('/\s+/', '', $companyname);
            }
            $sprep->userData = $custUserData;

			$customSpec->identity = $sprep;
            if ($autoConfiguration && empty($dhcp)) {
                $globalIPSettings = new CustomizationGlobalIPSettings();
                $globalIPSettings->dnsServerList = explode(',', $dns);

                $customSpec->globalIPSettings = $globalIPSettings;
                $fixedIp = new CustomizationFixedIp();
                $fixedIp->ipAddress = $ip;

                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = $fixedIp;
                $customizationIPSettings->gateway = array($gateway);
                $customizationIPSettings->subnetMask = $netmask;

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            } elseif (!empty($dhcp)) {
                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = new CustomizationDhcpIpGenerator();

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            } else {
                $customizationIPSettings = new CustomizationIPSettings();
                $customizationIPSettings->ip = new CustomizationDhcpIpGenerator();

                $adapterMapping = new CustomizationAdapterMapping();
                $adapterMapping->adapter = $customizationIPSettings;

                $adapterMappings[] = $adapterMapping;
                $customSpec->nicSettingMap = $adapterMappings;
            }
            $return = $vm_info->CustomizeVM_Task(array('spec' => $customSpec));
            while (in_array($return->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            logModuleCall("VMware", "Reset Windows Password", array('name' => $newVmname, 'spec' => $customSpec), $this->WgsVmwareObj->vmware_object_to_array($return));

            if ($return->info->state == 'success') {
                $status = 'success';

                return $status;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($return);

                return $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function vm_reset($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->ResetVM_Task();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function get_vm_recent_task($vm_name)
    {

        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('recentTask'));
        return $vm_info;
    }

    public function get_vm_recent_task_info($taskId)
    {
        $this->connect();
        $vm_info = $this->vsphere->findOneManagedObject('Task', $taskId, array('info'));
        return $vm_info;
    }

    public function get_all_existing_vms_for_traffic()
    {
        $this->connect();
        $all_vms = $this->vsphere->findAllManagedObjects('VirtualMachine', array('name')); //configStatus
        return $all_vms;
    }

    public function CreateVMSnapshot($vm_name, $SnapShotName, $SnapShotDescription)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $soapmsg['name'] = $SnapShotName;
            $soapmsg['description'] = $SnapShotDescription;
            $soapmsg['memory'] = true;
            $soapmsg['quiesce'] = true;
            $temp = $vm_info->CreateSnapshot_Task($soapmsg);
            while (in_array($temp->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($temp->info->state == 'success') {
                $state = $temp->info->state;
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($temp);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $temp);
    }

    public function RenameVMSnapshot($vm_name, $SnapShotName, $SnapShotDescription, $id, $origonal_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findOneManagedObject('VirtualMachineSnapshot', trim($origonal_name), array());
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $args = array('name' => $SnapShotName, 'description' => $SnapShotDescription);
            $ret = $vm_info->RenameSnapshot($args);
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function revertSnapshot($vm_name)
    {
        try {
            $this->connect();

            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('summary'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->revertToCurrentSnapshot_Task();

            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function recfact($array, $userid, $serviceid, $vm_save, $vm_rename, $vm_snapshot_list_not_found, $vm_loding, $vm_name)
    {
        $resArray = array();

        foreach ($array as $key => $value) {
            if (is_array($value->childSnapshotList)) {
                $resArray = $this->recfact($value->childSnapshotList, $userid, $serviceid, $vm_save, $vm_rename, $vm_snapshot_list_not_found, $vm_loding, $vm_name);
            } else {
                $resArray = $value->childSnapshotList;
            }
        }

        $accessTab = array();
        $data = Capsule::table('mod_vmware_settings')->where('uid', $userid)->where('sid', $serviceid)->get();
        $settingArr = explode(',', $data[0]->setting);
        $accessTab = array();
        foreach ($settingArr as $setting) {
            $accessTab[$setting] = 'yes';
        }
        if (count($array) > 0) {
            $snapshot = $array[0]->snapshot->toReference()->_;
            $id = $array[0]->id;
            $name = $array[0]->name;
            $description = $array[0]->description;
            $state = $array[0]->state;
            $backupManifest = $array[0]->namebackupManifest;
            $createTime = $array[0]->createTime;
            $timeArr = explode('T', substr($createTime, 0, 19));
            $html .= '<tr id="tblrow_' . $id . '">';
            if (empty($accessTab['removesnapshot']))
                $html .= '<td><input type="checkbox" class="multi_checkbox" id="checkbox_' . $id . '" value="' . $id . '" name="slected_sp[]"></td>';

            $html .= '<td>' . $timeArr[0] . ' ' . $timeArr[1] . '</td>';
            $html .= '<td><input type="hidden" value="' . $snapshot . '" id="orgional_name_' . $id . '" name="org_name[]"><input type="text" readonly="1" name="snapshot[]" value="' . $name . '" id="name_' . $id . '" class="readonlyinput"></td>';
            $html .= '<td><input type="hidden" value="' . $description . '" id="orgional_desc_' . $id . '"><textarea cols="20" rows="1" readonly="1" id="desc_' . $id . '">' . $description . '</textarea></td>';
            $html .= '<td><a href="javascript:void(0);" onclick="rename_snap_shot_button_action(this, ' . $id . ',\'' . $vm_save . '\',\'' . $vm_rename . '\',\'' . $vm_loding . '\',\'' . $vm_name . '\');
            " class="reb_btn" style="margin: 0;
            ">' . $vm_rename . '</a></td>';
            $html .= '</tr>';
        } else {
            $html = '';
        }
        print $html;

        return $resArray;
    }

    public function snapshotList($vm_name, $userid, $serviceid, $vm_save, $vm_rename, $vm_snapshot_list_not_found, $vm_loding)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('summary'));
        $ret = $vm_info->getSnapshot()->rootSnapshotList;
        $rets = $this->recfact($ret, $userid, $serviceid, $vm_save, $vm_rename, $vm_snapshot_list_not_found, $vm_loding, $vm_name);
        return $rets;
    }

    public function deleteVMSnapshot($vm_name, $SnapShotName)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));

            $soapmsg['consolidate'] = true;
            $soapmsg['removeChildren'] = false;
            $temp = $vm_info->RemoveSnapshot_Task($soapmsg);
            while (in_array($temp->info->state, array('running', 'queued'))) {
                sleep(1);
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return $temp;
    }

    public function removeAllSnapshot($vm_name)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->RemoveAllSnapshots_Task();
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function getVMSnapshot($vm_name)
    {
        $this->connect();
        $vm_info = $this->vsphere->findManagedObjectByName('VirtualMachine', $vm_name, array('configStatus'));
        $temp = $vm_info->SnapshotSection();
        return $temp;
    }

    public function removeSelectedSnapshot($origonal_name, $child)
    {
        try {
            $this->connect();
            $vm_info = $this->vsphere->findOneManagedObject('VirtualMachineSnapshot', trim($origonal_name), array());

            if (!$vm_info) {
                return array('state' => 'Vm not found', 'obj' => 'Vm not found');
            }
            $ret = $vm_info->RemoveSnapshot_Task(array('removeChildren' => $child));
            while (in_array($ret->info->state, array('running', 'queued'))) {
                sleep(1);
            }
            if ($ret->info->state == 'success') {
                $state = 'success';
            } else {
                $retArr = $this->WgsVmwareObj->vmware_object_to_array($ret);
                $state = $retArr['RetrievePropertiesResponse']['returnval']['propSet']['val']['error']['localizedMessage'];
            }
        } catch (Exception $ex) {
            $ret = $ex->getMessage();
        }

        return array('state' => $state, 'obj' => $ret);
    }

    public function GetWHMCS_UserName($id)
    {
        $result = Capsule::table('tblclients')->select('firstname', 'lastname')->where('id', $id)->get();
        return ucfirst($result[0]->firstname) . ' ' . ucfirst($result[0]->lastname);
    }

    public function vmwareGenerateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if (strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if (strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if (strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if (!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public function vmwareAssignIp($ipListArr, $new_vm_name, $dhcp = null, $serviceid = null, $upgrade = null)
    {   
        Capsule::Schema()->table('mod_vmware_ip_list', function ($table) {
            if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'sid'))
                $table->integer('sid')->nullable();
        });
        if (empty($dhcp) && count($ipListArr) > 0) {
            foreach ($ipListArr as $ipKey => $IpList) {
                if ($ipKey == 0) {
                    $sts = 1;
                } else {
                    $sts = 2;
                }
                if ($upgrade) {
                    $sts = 2;
                }
                try {
                    $count = Capsule::table('mod_vmware_ip_list')->where('ip', $IpList['ip'])->where('forvm', $new_vm_name)->count();
                    if ($count == 0) {
                        $updatedStatus = Capsule::table('mod_vmware_ip_list')
                            ->where('ip', $IpList['ip'])
                            ->update(
                                [
                                    'status' => $sts,
                                    'forvm' => $new_vm_name,
                                    'sid' => $serviceid
                                ]
                            );
                    }
                } catch (\Exception $e) {
                    logActivity("couldn't update mod_vmware_ip_list: {$e->getMessage()}");
                }
            }
        }
    }

    public function reconfigureConfig($memoryMB, $numCPUs, $vncPw, $vnc_port, $webSocifyPort, $cpuMhz, $params, $networkIp)
    {
        $config = new VirtualMachineConfigSpec();
        if ($networkIp)
            $config->annotation = "Created by WHMCS.\n User ID: {$params['userid']}\n Service ID: {$params['serviceid']}\n IP: {$networkIp}";
        else
            $config->annotation = "Created by WHMCS.\n User ID: {$params['userid']}\n Service ID: {$params['serviceid']}";
        $config->memoryMB = $memoryMB;
        $config->memoryHotAddEnabled = TRUE;
        $config->cpuHotAddEnabled = TRUE;
        $config->cpuHotRemoveEnabled = TRUE;
        $config->numCPUs = $numCPUs;
        $cpuResInfo = new ResourceAllocationInfo();
        $cpuResInfo->limit = $cpuMhz . 'MHz';
        if (!empty($cpuMhz))
            $config->cpuAllocation = $cpuResInfo;

        $memResInfo = new ResourceAllocationInfo();
        $memResInfo->limit = $memoryMB . 'MB';
        $config->memoryAllocation = $memResInfo;

        $vncEnabled = new OptionValue();
        $vncEnabled->key = "Remotedisplay.vnc.enabled";
        $vncEnabled->value = new SoapVar("FALSE", XSD_STRING, 'xsd:string');

        $vncPort = new OptionValue();
        $vncPort->key = "Remotedisplay.vnc.port";
        $vncPort->value = new SoapVar($vnc_port, XSD_STRING, 'xsd:string');

        $vncPwd = new OptionValue();
        $vncPwd->key = "Remotedisplay.vnc.password";
        $vncPwd->value = new SoapVar($vncPw, XSD_STRING, 'xsd:string');

        $vncWebscoketEnabled = new OptionValue();
        $vncWebscoketEnabled->key = "RemoteDisplay.vnc.WebSocket.enabled";
        $vncWebscoketEnabled->value = new SoapVar("TRUE", XSD_STRING, 'xsd:string');

        $vncWebscoketPort = new OptionValue();
        $vncWebscoketPort->key = "RemoteDisplay.vnc.WebSocket.port";
        $vncWebscoketPort->value = new SoapVar($webSocifyPort, XSD_STRING, 'xsd:string');

        $extraConfig = array($vncEnabled, $vncPort, $vncPwd, $vncWebscoketEnabled, $vncWebscoketPort);
        $config->extraConfig = $extraConfig;
        return $config;
    }

    public function vmwareUpdateServiceField($serviceid, $newVmname, $ip, $params, $vnc_port, $vncPw, $admin, $webSocifyPort, $dc = null, $hostsystem_name = null, $osport = null, $reinstall = null, $osType = null, $guetOsVersion = null, $customFields = null, $dhcp = null, $existingMacAddress = null)
    {

        $serviceUserName = "Administrator";
        if ($osType == 'Linux' || $osType == 'Others') {
            $serviceUserName = "root";
        }
        $row = Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count();
        $setting = 'migrate';
        $settingValues = [
            'setting' => $setting,
            'uid' => $params['userid'],
            'sid' => $params['serviceid'],
        ];
        if ($row == 0) {
            try {
                Capsule::table('mod_vmware_settings')->insert($settingValues);
            } catch (Exception $ex) {
                logActivity("could't insert into table mod_vmware_settings error: {$ex->getMessage()}");
            }
        } else {
            try {
                Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->update($settingValues);
            } catch (Exception $ex) {
                logActivity("could't update into table mod_vmware_settings error: {$ex->getMessage()}");
            }
        }
        if (empty($dhcp)) {
            try {
                $updatedStatus = Capsule::table('tblhosting')
                    ->where('id', $serviceid)
                    ->update(
                        [
                            'username' => $serviceUserName,
                            'dedicatedip' => ($osport != "")?$ip.":".$osport:$ip,
                        ]
                    );
            } catch (\Exception $e) {
                logActivity("couldn't update tblhosting: {$e->getMessage()}");
            }
        } else {
            try {
                $updatedStatus = Capsule::table('tblhosting')
                    ->where('id', $serviceid)
                    ->update(
                        [
                            'username' => $serviceUserName,
                        ]
                    );
            } catch (\Exception $e) {
                logActivity("couldn't update tblhosting: {$e->getMessage()}");
            }
        }


        $pid = $params['pid'];
        if (empty($params['vm_password']))
            $params['vm_password'] = '';
        if (empty($reinstall)) {
            $vmnameFieldId = $this->vmwareGetCfId($pid, 'vm_name');
            $vncFieldId = $this->vmwareGetCfId($pid, 'vnc_detail');
            $hostname_dc = $this->vmwareGetCfId($pid, 'hostname_dc');
            $macFieldId = $this->vmwareGetCfId($pid, 'mac_address');
            $vm_passwordFieldId = $this->vmwareGetCfId($pid, 'vm_password');
            $command = "updateclientproduct";
            $adminuser = $admin;
            $values["serviceid"] = $params['serviceid'];
            $values["servicepassword"] = $params['vm_password'];
            $values["customfields"] = base64_encode(serialize(array($vmnameFieldId => $newVmname, $vncFieldId => $vnc_port . ' ' . $vncPw . ' ' . $webSocifyPort, $hostname_dc => $hostsystem_name . '&&' . $dc, $vm_passwordFieldId => $params['vm_password'], $macFieldId => $existingMacAddress)));
            $results = localAPI($command, $values, $adminuser);
        } elseif (!empty($reinstall)) {
            $customFieldVal = $customFields;
            $vmnameFieldId = $this->vmwareGetCfId($pid, 'vm_name');
            $datacenter_cf_id = $this->vmwareGetCfId($pid, $customFieldVal['datacenter_field']);
            $os_type_cf_id = $this->vmwareGetCfId($pid, $customFieldVal['os_type_field']);
            $os_version_cf_id = $this->vmwareGetCfId($pid, $customFieldVal['os_version_field']);
            $vncFieldId = $this->vmwareGetCfId($pid, 'vnc_detail');
            $hostname_dc = $this->vmwareGetCfId($pid, 'hostname_dc');
            $reinstallFieldId = $this->vmwareGetCfId($pid, 'reinstall');
            $vm_passwordFieldId = $this->vmwareGetCfId($pid, 'vm_password');

            $command = "updateclientproduct";
            $adminuser = '';
            $values["serviceid"] = $params['serviceid'];
            $values["servicepassword"] = $params['vm_password'];
            $values["customfields"] = base64_encode(serialize(array($vmnameFieldId => $newVmname, $vncFieldId => $vnc_port . ' ' . $vncPw . ' ' . $webSocifyPort, $hostname_dc => $hostsystem_name . '&&' . $dc, $vm_passwordFieldId => $params['vm_password'], $os_type_cf_id => $osType, $os_version_cf_id => $guetOsVersion)));
            $results = localAPI($command, $values, $adminuser);

            if ($params['configoption1'] == 'on') {
                $getconfigGroupIdQuery = Capsule::table('tblproductconfiglinks')->where('pid', (int) $pid)->first();
                $getconfigGroupId = $getconfigGroupIdQuery->gid;
                $getOsFamilyConfigoptionsQuery = Capsule::table('tblproductconfigoptions')->where('gid', (int) $getconfigGroupId)->where('optionname', 'like', '%guest_os_family%')->first();
                $getOsFamilyConfigoptionsId = $getOsFamilyConfigoptionsQuery->id;
                $getOsFamilyConfigSubOptionQuery = Capsule::table('tblproductconfigoptionssub')->where('configid', (int) $getOsFamilyConfigoptionsId)->where('optionname', 'like', '%' . $osType . '%')->first();
                $getOsFamilyConfigSubOptionId = $getOsFamilyConfigSubOptionQuery->id;

                $getOsVersionConfigoptionsQuery = Capsule::table('tblproductconfigoptions')->where('gid', (int) $getconfigGroupId)->where('optionname', 'like', '%guest_os_version%')->first();
                $getOsVersionConfigoptionsId = $getOsVersionConfigoptionsQuery->id;
                $getOsVersionConfigSubOptionQuery = Capsule::table('tblproductconfigoptionssub')->where('configid', (int) $getOsVersionConfigoptionsId)->where('optionname', 'like', '%' . $guetOsVersion . '%')->first();
                $getOsVersionConfigSubOptionId = $getOsVersionConfigSubOptionQuery->id;

                Capsule::table('tblhostingconfigoptions')->where('relid', (int) $serviceid)->where('configid', (int) $getOsFamilyConfigoptionsId)->update(['optionid' => $getOsFamilyConfigSubOptionId]);
                Capsule::table('tblhostingconfigoptions')->where('relid', (int) $serviceid)->where('configid', (int) $getOsVersionConfigoptionsId)->update(['optionid' => $getOsVersionConfigSubOptionId]);
            }
        }
    }

    public function vmwareGetCfId($pid, $fieldName)
    {
        $response = Capsule::table('tblcustomfields')->select('id')->where('relid', $pid)->where('type', 'product')->where('fieldname', 'like', '%' . $fieldName . '%')->first();
        $response = (array) $response;
        return $response['id'];
    }

    public function vmwareUpdateCfValue($serviceId, $fieldid, $value, $dbConn = NULL)
    {
        $query = mysqli_query($dbConn, "SELECT `value` FROM `tblcustomfieldsvalues` WHERE `fieldid` = '" . (int) $fieldid . "' AND `relid` = '" . (int) $serviceId . "'");
        $rows = mysqli_num_rows($query);
        if ($rows > 0)
            mysqli_query($dbConn, "UPDATE `tblcustomfieldsvalues` SET `value` = '" . $value . "' WHERE `fieldid` = '" . (int) $fieldid . "' AND `relid` = '" . (int) $serviceId . "'") or die(mysqli_error($dbConn));
        else
            mysqli_query($dbConn, "INSERT INTO `tblcustomfieldsvalues` (`fieldid`,`relid`,`value`) VALUES ('" . (int) $fieldid . "','" . (int) $serviceId . "','" . $value . "')") or die(mysqli_error($dbConn));
    }

    public function checkVmPortInDb($reinstall = null)
    {
        if (!empty($reinstall)) {
            $getPortSettingQuery = Capsule::table('tblconfiguration')->select('value')->where('setting', 'vmport')->first();
            $getPortSettingQuery = (array) $getPortSettingQuery;
            $getPortSettingQuery1 = Capsule::table('tblconfiguration')->select('value')->where('setting', 'vmvncport')->first();
            $getPortSettingQuery1 = (array) $getPortSettingQuery1;
            return array('websockifyport' => $getPortSettingQuery['value'], 'vncport' => $getPortSettingQuery1['value']);
        } else {
            $getPortSettingCount = Capsule::table('tblconfiguration')->where('setting', 'vmport')->count();
            if ($getPortSettingCount == 0) {
                Capsule::table('tblconfiguration')->insert(['setting' => 'vmport', 'value' => '']);
            }
            $getVncPortSettingCount = Capsule::table('tblconfiguration')->where('setting', 'vmvncport')->count();
            if ($getVncPortSettingCount == 0) {
                Capsule::table('tblconfiguration')->insert(['setting' => 'vmvncport', 'value' => '']);
            }
            $getPortSettingQuery = Capsule::table('tblconfiguration')->select('value')->where('setting', 'vmport')->get();
            $getPortSettingQuery = (array) $getPortSettingQuery[0];
            $getPortSettingQuery1 = Capsule::table('tblconfiguration')->select('value')->where('setting', 'vmvncport')->get();
            $getPortSettingQuery1 = (array) $getPortSettingQuery1[0];
            return array('websockifyport' => $getPortSettingQuery['value'], 'vncport' => $getPortSettingQuery1['value']);
        }
    }

    public function updateVmPortInDb($webSocifyPort, $vncport)
    {
        Capsule::table('tblconfiguration')->where('setting', 'vmport')->update(['value' => $webSocifyPort]);
        Capsule::table('tblconfiguration')->where('setting', 'vmvncport')->update(['value' => $vncport]);
    }

    public function vmware_getCustomFieldVal($id, $relid)
    {
        $result = Capsule::table('tblcustomfieldsvalues')->select('value')->where('fieldid', $id)->where('relid', $relid)->get();
        return $result[0]->value;
    }

    public function storeVmwareLogs($sid, $new_vm_name = "", $description = "", $status = "", $reinstall = null, $dbConn = null, $uname = null)
    {

        if (!empty($reinstall)) {
            $query = mysqli_query($dbConn, "SELECT value FROM `tbladdonmodules` WHERE `module` = 'vmware' AND `setting` = 'enable_log'");
            $data = mysqli_fetch_assoc($query);
            $enableLog->value = $data['value'];
        } else
            $enableLog = Capsule::table('tbladdonmodules')->where('module', 'vmware')->where('setting', 'enable_log')->first();
        if ($enableLog->value == 'on') {
            global $remote_ip;
            if (empty($uname))
                $uname = $this->returnAccessUsername();
            $data = [
                "sid" => $sid,
                "date" => date("Y-m-d H:i:s"),
                "description" => $description,
                "username" => $uname,
                "vmname" => $new_vm_name,
                "ip" => $remote_ip,
                "status" => $status
            ];
            if (!empty($reinstall)) {
                $remote_ip = '';
                if (getenv('HTTP_CLIENT_IP'))
                    $remote_ip = getenv('HTTP_CLIENT_IP');
                else if (getenv('HTTP_X_FORWARDED_FOR'))
                    $remote_ip = getenv('HTTP_X_FORWARDED_FOR');
                else if (getenv('HTTP_X_FORWARDED'))
                    $remote_ip = getenv('HTTP_X_FORWARDED');
                else if (getenv('HTTP_FORWARDED_FOR'))
                    $remote_ip = getenv('HTTP_FORWARDED_FOR');
                else if (getenv('HTTP_FORWARDED'))
                    $remote_ip = getenv('HTTP_FORWARDED');
                else if (getenv('REMOTE_ADDR'))
                    $remote_ip = getenv('REMOTE_ADDR');

                mysqli_query($dbConn, "INSERT INTO `mod_vmware_logs` (`sid`,`date`,`description`,`username`,`vmname`,`ip`,`status`) VALUES ('" . (int) $sid . "','" . date("Y-m-d H:i:s") . "','" . (string) $description . "','" . (string) $uname . "','" . $new_vm_name . "','" . (string) $remote_ip . "','" . (string) $status . "')") or die(mysqli_error($dbConn));
            } else {
                Capsule::table('mod_vmware_logs')->insert($data);
            }
        }
    }

    public function returnAccessUsername()
    {
        if (isset($_SESSION['adminid'])) {
            $result = Capsule::table("tbladmins")->where("id", $_SESSION['adminid'])->first();
            $username = '<a href="configadmins.php?action=manage&id=' . $result->id . '" target="_blank">' . ucfirst($result->username) . '</a>';
        } else {
            if (isset($_SESSION['cid'])) {
                $username = "Sub-Account " . $_SESSION['cid'];
            } else {
                if (isset($_SESSION['uid'])) {
                    $result = Capsule::table("tblclients")->where("id", $_SESSION['uid'])->first();
                    $username = '<a href="clientssummary.php?userid=' . $result->id . '" target="_blank">' . ucfirst($result->firstname) . '</a>';
                } else {
                    $username = "System/Automated";
                }
            }
        }
        return $username;
    }

    public function createPortGroup($hostsystem_name)
    {
        try {
            $this->connect();
            $host = $this->vsphere->findManagedObjectByName('HostSystem', $hostsystem_name, array('configManager'));
            $specification = new HostPortGroupSpec();
            $specification->name = "testt";
            $specification->vlanId = 0;
            $specification->vswitchName = "vSwitch0";
            $policy = new HostNetworkPolicy();
            $security = new HostNetworkSecurityPolicy();
            $security->allowPromiscuous = true;
            $security->forgedTransmits = false;
            $security->macChanges = false;
            $policy->security = $security;
            $specification->policy = $policy;
            $ret = $host->configManager->networkSystem->AddPortGroup(["portgrp" => $specification]);
            return $ret;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}