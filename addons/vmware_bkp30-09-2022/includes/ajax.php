<?php

use Illuminate\Database\Capsule\Manager as Capsule;

ini_set('memory_limit', '1024M');

$customaction = filter_var($_POST['customaction'], FILTER_SANITIZE_STRING);

$sid = filter_var($_REQUEST['sid'], FILTER_SANITIZE_STRING);

$id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);

if (!empty($customaction)) {

    ini_set('max_execution_time', 1200);

    $serverid = filter_var($_REQUEST['serverid'], FILTER_SANITIZE_STRING);

    if (isset($serverid) || $sid) {

        if ($sid)

            $serverid = $sid;

        if (!empty($serverid)) {

            $serverData = Capsule::table('mod_vmware_server')->where('id', $serverid)->get();

            //            $path = str_replace("addons/vmware/includes", "", __DIR__);

            $path = __DIR__ . '/../../../';

            require_once $path . 'servers/vmware/vmwarephp/Bootstrap.php';

            require_once $path . 'servers/vmware/vmclass.php';

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $vmWare->vmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, html_entity_decode($decryptPw['password']));
        }
    }

    switch ($customaction) {

        case 'getOsversion':

            $option = '';

            if (file_exists(dirname(dirname(__FILE__)) . '/guestOs/guestOsIdentifier.txt')) {

                $GuestOsVariableArr = file(dirname(dirname(__FILE__)) . '/guestOs/guestOsIdentifier.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            } else {

                $option = 'guestOsIdentifier.txt file missing';
            }

            $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);

            $osVersion = filter_var($_POST['osVersion'], FILTER_SANITIZE_STRING);

            foreach ($GuestOsVariableArr as $key => $OsVersions) {

                $OsVersionsArr = explode('-', $OsVersions);

                if (empty($id)) {

                    if ($OsVersionsArr[0] == $osVersion) {

                        $option .= '<option value="' . $OsVersionsArr[1] . '">' . $OsVersionsArr[1] . '</option>';
                    }
                } elseif (!empty($id)) {

                    if ($OsVersionsArr[1] == $osVersion) {

                        $option = $OsVersionsArr[2];
                    }
                }
            }

            echo $option;



            exit();

            break;

        case 'getisofiles':

            if (!empty($serverid) && $serverid != "undefined") {

                try {

                    $datastore = $vms->list_datastores(); // $this->vsphere->findManagedObjectByName('Datastore','56c1c14e-9680622e-969b-d8cb8abf91ce',array('summary'));

                    $count = 0;



                    $hdbss = new HostDatastoreBrowserSearchSpec();

                    $fqf = new FileQueryFlags();

                    $fqf->setFileType = true;

                    $hdbss->setDetails = $fqf;

                    $hdbss->setSearchCaseInsensitive = false;

                    $hdbss->setSortFoldersFirst = true;

                    foreach ($datastore as $ds) {

                        $ret = $ds->getBrowser()->searchDatastoreSubFolders_Task(array('datastorePath' => '[' . trim(filter_var($_POST['datastore'], FILTER_SANITIZE_STRING)) . '] iso', 'querySpec' => $hdbss));

                        if (!empty($ret)) {

                            while (in_array($ret->info->state, array('running', 'queued'))) {

                                sleep(1);
                            }

                            if (!$ret->info->error) {

                                $isoFiles = '';

                                foreach ($ret->info->result[0]->file as $files) {

                                    if (strpos($files->path, '.iso') || strpos($files->path, '.ISO')) {

                                        $isoFiles .= '<option value="' . $files->path . '">' . $files->path . '</option>';
                                    }
                                }

                                echo $isoFiles;
                            }

                            //                    else {

                            //                        $isoFiles = 'Not found';

                            //                    }

                        }
                    }
                } catch (Exception $ex) {

                    logActivity("Error in get ISO files Error: " . $ex->__toString());
                }
            } else {

                $option = 'Not found';
            }

            exit();

            break;

        case 'getdatastore':

            if (!empty($serverid)) {

                try {

                    $option = '';

                    $datastores = $vms->list_datastores();

                    $datastoreIds = $WgsVmwareObj->vmware_object_to_array($datastores[0]);



                    if (isset($datastoreIds['RetrievePropertiesResponse']['returnval'])) {

                        if (isset($datastoreIds['RetrievePropertiesResponse']['returnval'][1])) {

                            foreach ($datastoreIds['RetrievePropertiesResponse']['returnval'] as $datastoresValue) {

                                $option .= '<option value="' . $datastoresValue['propSet'][0]['val'] . '">' . $datastoresValue['propSet'][0]['val'] . '</option>';
                            }
                        } else {

                            $option = '<option value="' . $datastoreIds['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'] . '">' . $datastoreIds['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'] . '</option>';
                        }
                    } else {

                        $option = 'Not found';
                    }
                } catch (Exception $ex) {

                    logActivity("Error in get datastore Error: " . $ex->__toString());
                }
            } else {

                $option = 'Not found';
            }

            echo $option;

            exit();

            break;

        case 'getdatacenter':

            if (!empty($serverid)) {

                try {

                    $option = '';

                    $datacenter = $vms->list_datacenters();

                    if (is_array($datacenter) && !empty($datacenter)) {

                        foreach ($datacenter as $key => $value) {

                            $option .= '<option value="' . $value->name . '">' . ucfirst($value->name) . '</option>';
                        }

                        print_r(json_encode(array('result' => 'success', 'option' => $option)));
                    } else {

                        print_r(json_encode(array('result' => 'Not Found')));
                    }
                } catch (Exception $ex) {

                    print_r(json_encode(array('result' => $ex->getMessage())));
                }
            } else {

                print_r(json_encode(array('result' => 'vCenter Server Id Not Found')));
            }

            exit();

            break;

        case 'getadditional':

            foreach (Capsule::table('mod_vmware_ip_list')->where('status', 2)->where('forvm', filter_var($_POST['vmname'], FILTER_SANITIZE_STRING))->get() as $additional) {

                $additional = (array) $additional;

                $servername = Capsule::table('mod_vmware_server')->select('server_name')->where('id', $additional['server_id'])->get();

                $servername = (array) $servername[0];

                $additional = array_merge($servername, $additional);

                $additionalIpHtml .= '<tr class="ajaxtr_' . filter_var($_POST['id'], FILTER_SANITIZE_STRING) . '">';

                $additionalIpHtml .= '<td>&nbsp;</td>'; //' . $additional['forvm'] . '

                $additionalIpHtml .= '<td>--&nbsp;</td>'; //' . $additional['forvm'] . '

                $additionalIpHtml .= '<td>' . $additional['ip'] . '</td>';

                $additionalIpHtml .= '<td>' . $additional['netmask'] . '</td>';

                $additionalIpHtml .= '<td>' . $additional['gateway'] . '</td>';

                $additionalIpHtml .= '<td>' . $additional['dns'] . '</td>';

                $additionalIpHtml .= '<td>' . $additional['macaddress'] . '</td>';

                //                $additionalIpHtml .= '<td>' . $additional['datacenter'] . '</td>';

                //$additionalIpHtml .= '<td> ' . $additional['server_name'] . '</td>';

                //$additionalIpHtml .= '<td>&nbsp;</td>'; //' . $additional['server'] . '

                $additionalIpHtml .= '<td><span class="label active">Assigned</span></td>';

                $additionalIpHtml .= '<td><a href="' . $modulelink . '&action=ip_map&edit_ip=' . $additional['id'] . '">

                                    <img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>

                                        &nbsp;

                                    <!--a href="' . $modulelink . '&action=ip_map&delete_ip=' . $additional['id'] . '" onclick="return confirm(\'Are you sure delete this row ?\');">

                                                    <img src="images/delete.gif" width="16" height="16" border="0" alt="Cancel &amp; Delete">

                                                </a-->

                                            </td>';

                $additionalIpHtml .= '</tr>';
            }

            echo $additionalIpHtml;

            exit();

            break;

        case 'checkvm':

            $count = Capsule::table('mod_vmware_ip_list')->where('forvm', filter_var($_POST['vmname'], FILTER_SANITIZE_STRING))->count();

            if ($count > 0)

                echo 2;

            else

                echo 1;

            exit();

            break;

        case 'getallvms':

            $allVms = $vms->get_all_existing_vms();

            $vmOptions = '';

            foreach ($allVms as $key => $value) {

                $vmName = $value->name;

                $vmId = $value->reference->_;

                $hostObj = $value->runtime->host->reference->_;

                if ($hostObj == $_POST['host_obj'])

                    $vmOptions .= '<option value="' . urldecode($vmName) . '">' . urldecode($vmName) . '</option>';
            }

            if (empty($vmOptions))

                $vmOptions = 'Not Found';

            echo $vmOptions;

            exit();

            break;

        case 'getips':

            $datacenter = filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING);

            $sid = filter_var($_POST['sid'], FILTER_SANITIZE_STRING);

            $hostname = filter_var($_POST['hostname'], FILTER_SANITIZE_STRING);

            if (Capsule::table('mod_vmware_ip_list')->where('datacenter', $datacenter)->where('status', '0')->where('server_id', $sid)->where('hostname', $hostname)->count() > 0) {

                $options = '';

                foreach (Capsule::table('mod_vmware_ip_list')->where('datacenter', $datacenter)->where('status', '0')->where('server_id', $sid)->where('hostname', $hostname)->get() as $result) {

                    $options .= '<option value="' . $result->ip . '">' . $result->ip . '</option>';
                }
            } else {

                $options = 'Not Found';
            }

            print_r(json_encode(array('options' => $options)));

            exit();

            break;

        case 'getproductsetting':

            $productDetail = Capsule::table('tblproducts')->where('id', filter_var($_POST['pid'], FILTER_SANITIZE_STRING))->get();

            $productDetail = (array) $productDetail[0];

            if ($productDetail['configoption1'] == '') {

                echo true;
            } else

                echo false;

            exit();

            break;

        case 'getexistingvm':

            $vmwares = $vms->get_all_vms();

            if (count($vmwares) > 0 && !empty($vmwares)) {

                $vmoption = '';



                $vmArr = array();

                foreach ($vmwares as $key => $vm) {

                    $vmArr[] = urldecode($vm['hostname']);
                }

                sort($vmArr);

                foreach ($vmArr as $vmName) {

                    $vmoption .= '<option value="' . $vmName . '">' . $vmName . '</option>';
                }
            } else {

                $vmoption = 'Not found';
            }

            echo $vmoption;

            exit();

            break;

        case 'declineMigrate':

            $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);

            $data = [

                'status' => '2',

                'reason' => $reason

            ];

            try {

                Capsule::table('mod_vmware_migration_list')->where('id', filter_var($_POST['id'], FILTER_SANITIZE_STRING))->update($data);
            } catch (Exception $ex) {

                echo '<div class="errormsg">' . $ex->getMessage() . '</div>';

                exit();
            }



            $user_migration_content = '<p>' . $LANG['mailyourserver'] . ' (' . filter_var($_POST['vm_name'], FILTER_SANITIZE_STRING) . ') ' . $LANG['maildeclinedmsg'] . '.</p><p>' . $LANG['maildclnreason'] . ': ' . $reason . '</p><p> ' . $LANG['maildeclnsupport'] . '.</p>';



            $adminQuery = Capsule::table('tbladmins')->get();

            $adminuser = $adminQuery[0]->id;

            $command = "sendemail";

            $values["id"] = filter_var($_POST['serviceid'], FILTER_SANITIZE_STRING);

            $values["messagename"] = 'VMware Migration Notification Email';

            $values["customvars"] = base64_encode(serialize(array("user_migration_content" => $user_migration_content)));

            $results = localAPI($command, $values, $adminuser);

            if ($results['result'] == 'success') {

                echo '<div class="successmsg">Successfully Declined.</div>';
            } else {

                echo '<div class="errormsg">' . $results['message'] . '</div>';
            }

            exit();

            break;

        case 'acceptMigrate':

            try {

                $serviceid = filter_var($_POST['serviceid'], FILTER_SANITIZE_STRING);

                $getPidArr = Capsule::table('tblhosting')->select('packageid')->where('id', $serviceid)->get(); // to do

                $pid = $getPidArr[0]->packageid;



                $customFieldIdArr = Capsule::table('tblcustomfields')->select('id')->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%hostname_dc%')->get();



                $customFieldId = $customFieldIdArr[0]->id;



                $vm_name = filter_var($_POST['vm_name'], FILTER_SANITIZE_STRING);

                $info = $vms->get_vm_info($vm_name);

                $vmslist = $WgsVmwareObj->vmware_object_to_array($info);

                if (empty($vmslist)) {

                    $state = 'error';

                    $result = '<div class="errorbox"><strong>Error: Your request failed</strong><br>Vm not found</div>';

                    print_r(json_encode(array('result' => $state, 'msg' => $result)));

                    exit();
                }

                $hostsystem_name = filter_var($_POST['host_to'], FILTER_SANITIZE_STRING);

                $resource_pool_id = filter_var($_POST['r_pool'], FILTER_SANITIZE_STRING);



                $powerState = $vms->get_vm_info($vm_name)->summary->runtime->powerState;

                $priority = 'highPriority';

                $ret = $vms->migrate_vm($vm_name, $hostsystem_name, $resource_pool_id, $powerState, $priority);

                logModuleCall("VMware", "migrate vm", array('name' => $vm_name, 'resource_pool_id' => $resource_pool_id, 'hostname' => $hostsystem_name), $WgsVmwareObj->vmware_object_to_array($ret['obj']));

                if ($ret['state'] == 'success' || $ret['state'] == '') {

                    $data = [

                        'status' => '1',

                    ];

                    try {

                        Capsule::table('mod_vmware_migration_list')->where('id', filter_var($_POST['id'], FILTER_SANITIZE_STRING))->update($data); // todo

                    } catch (Exception $ex) {

                        $state = 'error';

                        $result = '<div class="errorbox"><strong>Error: Your request failed</strong><br>' . $ex->getMessage() . '</div>';

                        print_r(json_encode(array('result' => $state, 'msg' => $result)));

                        exit();
                    }



                    $user_migration_content = '<p><b>' . $LANG['mailaccetcongrts'] . '.</b></p><p>' . $LANG['mailyourserver'] . ' (' . filter_var($_POST['vm_name'], FILTER_SANITIZE_STRING) . ') ' . $LANG['mailacceptmsg'] . '.</p>';



                    $admin = Capsule::table('tbladmins')->select('id')->get();

                    $adminuser = $admin[0]->id;



                    $updatePcommand = "updateclientproduct";

                    $updatePvalues["serviceid"] = $serviceid;

                    $updatePvalues["customfields"] = base64_encode(serialize(array($customFieldId => $hostsystem_name . '&&' . filter_var($_POST['datacenter'], FILTER_SANITIZE_STRING))));



                    $updatePresults = localAPI($updatePcommand, $updatePvalues, $adminuser);



                    $command = "sendemail";

                    $values["id"] = $serviceid;

                    $values["messagename"] = 'VMware Migration Notification Email';

                    $values["customvars"] = base64_encode(serialize(array("user_migration_content" => $user_migration_content)));

                    $results = localAPI($command, $values, $adminuser);



                    if ($results['result'] == 'success') {
                    } else {

                        $state = 'error';

                        $result = '<div class="errorbox"><strong>Error: Your request failed</strong><br>' . $results['message'] . '</div>';

                        print_r(json_encode(array('result' => $state, 'msg' => $result)));

                        exit();
                    }



                    $state = 'success';

                    $result = '<div class="successbox"><strong>Sucessfully saved</strong><br>Server has been migrate.</div>';
                } else {

                    $state = 'error';

                    $result = '<div class="errorbox"><strong>Error: Your request failed</strong><br>' . $ret['state'] . '</div>';
                }
            } catch (Exception $ex) {

                $state = 'error';

                $result = '<div class="errorbox"><strong>Error: Your request failed</strong><br>' . $ex->getMessage() . '</div>';
            }



            print_r(json_encode(array('result' => $state, 'msg' => $result)));



            exit();

            break;

        case 'get_dc_host':

            try {

                // $get_datacenter = $vms->list_datacenters_host(filter_var($_POST['dc'], FILTER_SANITIZE_STRING));
                $get_datacenterHosts = $vms->list_datacenters_host(filter_var($_POST['dc'], FILTER_SANITIZE_STRING));
                if ($get_datacenterHosts) {
                    $hostname = '';
                    foreach($get_datacenterHosts as $hostKey => $hostRes){
                        foreach($hostRes as $hostData){
                            if($hostData)
                                $hostname .= '<option value="' . $hostData->name . '" obj="' . $hostData->reference->_ . '">' . $hostData->name . '</option>';
                        }
                    }
                } else {
                    print_r(json_encode(array('result' => 'Records not found!')));
                    exit();
                }
                // if ($get_datacenter) {

                //     $hostname = '';

                //     $resourcePool = '';

                //     foreach ($get_datacenter as $getDatacenterHostArr) {

                //         if (count($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet']) > 0 && !empty($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'])) {



                //             foreach ($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $datacenterList) {

                //                 if ($datacenterList['name'] == 'host') {

                //                     if (count($datacenterList['val']['ManagedObjectReference']) > 1) {
                //                         $host = $datacenterList['val']['ManagedObjectReference'];
                //                     } else {
                //                         $host = array($datacenterList['val']['ManagedObjectReference']);
                //                     }

                //                     foreach ($host as $hostValue) {

                //                         $host_resource_arr = $WgsVmwareObj->vmware_object_to_array($vms->get_host_resources($hostValue));

                //                         foreach ($host_resource_arr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $hostResValue) {

                //                             if ($hostResValue['name'] == 'name') {

                //                                 $hostname .= '<option value="' . $hostResValue['val'] . '" obj="' . $hostValue . '">' . $hostResValue['val'] . '</option>';
                //                             }
                //                         }
                //                     }
                //                 }
                //             }
                //         } else {

                //             $hostname = 'Not Found';
                //         }
                //     }
                // } else {

                //     print_r(json_encode(array('result' => 'Records not found!')));

                //     exit();
                // }

                if ($hostname == 'Not Found')

                    print_r(json_encode(array('result' => 'Not Found')));

                else

                    print_r(json_encode(array('result' => 'succes', 'hostname' => $hostname)));
            } catch (Exception $ex) {

                print_r(json_encode(array('result' => $ex->getMessage())));
            }

            exit();

            break;

        case 'get_host_resourcepool':

            try {

                $host = $vms->get_host_parent(filter_var($_POST['host_obj'], FILTER_SANITIZE_STRING));

                $host_obj = $host->parent->reference->_;

                $hostType = $host->parent->reference->type;

                $resourceRef = $vms->get_host_resource_pool($host_obj, $hostType)->resourcePool->reference->_;

                if ($resourceRef) {

                    $resourcePoolName = $vms->getResourcePoolName($resourceRef)->name;

                    $resourcePool = '<option value="' . $resourceRef . '">' . $resourceRef . ' (' . $resourcePoolName . ')</option>';

                    print_r(json_encode(array('result' => 'succes', 'resourcepool' => $resourcePool)));
                } else {

                    print_r(json_encode(array('result' => 'Not Found')));
                }
            } catch (Exception $ex) {

                print_r(json_encode(array('result' => $ex->getMessage())));
            }

            exit();

            break;

        case 'get_all_host_resources':

            try {

                $allVms = $vms->get_all_existing_vms();

                $vmOptions = '';

                if (filter_var($_POST['existvm'], FILTER_SANITIZE_STRING) == 'true') {

                    foreach ($allVms as $key => $value) {

                        $vmName = $value->name;

                        $vmId = $value->reference->_;

                        $hostObj = $value->runtime->host->reference->_;

                        $powerState = $value->summary->runtime->powerState;

                        if ($hostObj == $_POST['host_obj'] && $powerState == 'poweredOff')

                            $vmOptions .= '<option value="' . urldecode($vmName) . '">' . urldecode($vmName) . '</option>';
                    }
                } else {

                    $vmOptions = '';
                }

                if (empty($vmOptions))

                    $vmOptions = 'Not Found';

                print_r(json_encode(array('vm' => $vmOptions)));
            } catch (Exception $ex) {

                print_r(json_encode(array('error' => $ex->getMessage())));
            }

            exit();

            break;

        case 'get_all_host_network':

            try {

                $host_resource_arr = $WgsVmwareObj->vmware_object_to_array($vms->get_host_network(filter_var($_POST['host_obj'], FILTER_SANITIZE_STRING)));



                foreach ($host_resource_arr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $value) {



                    if ($value['name'] == 'network') {

                        if (count($value['val']['ManagedObjectReference']) > 1) {

                            $networkArr = $value['val']['ManagedObjectReference'];
                        } else {

                            $networkArr = array($value['val']['ManagedObjectReference']);
                        }
                    }
                }



                if ($networkArr) {

                    $networkAdaptor = '';

                    foreach ($networkArr as $value) {

                        if (strchr($value, 'network') || strchr($value, 'Network')) {

                            $network = $WgsVmwareObj->vmware_object_to_array($vms->get_network($value));

                            foreach ($network['RetrievePropertiesResponse']['returnval']['propSet'] as $networkVal) {

                                if ($networkVal['name'] == 'name')

                                    $networkAdaptor .= '<option value="' . $network['RetrievePropertiesResponse']['returnval']['obj'] . '">' . $networkVal['val'] . '</option>';
                            }
                        } elseif (strchr($value, 'dvportgroup')) {

                            $network = $WgsVmwareObj->vmware_object_to_array($vms->get_dv_port_group($value));

                            foreach ($network['RetrievePropertiesResponse']['returnval']['propSet'] as $networkVal) {

                                if ($networkVal['name'] == 'config')

                                    $networkAdaptor .= '<option value="' . $networkVal['val']['key'] . '">' . $networkVal['val']['name'] . '</option>';
                            }
                        }
                    }
                } else {

                    $networkAdaptor = 'Not Found';
                }



                print_r(json_encode(array('network' => $networkAdaptor)));
            } catch (Exception $ex) {

                print_r(json_encode(array('error' => $ex->getMessage())));
            }



            exit();

            break;

        case 'get_all_host_datastores':

            try {

                $host_resource_arr = $WgsVmwareObj->vmware_object_to_array($vms->get_host_datastores(filter_var($_POST['host_obj'], FILTER_SANITIZE_STRING)));



                foreach ($host_resource_arr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $value) {



                    if ($value['name'] == 'datastore') {

                        if (count($value['val']['ManagedObjectReference']) > 1) {

                            $datastoreArr = $value['val']['ManagedObjectReference'];
                        } else {

                            $datastoreArr = array($value['val']['ManagedObjectReference']);
                        }
                    }
                }

                if ($datastoreArr) {

                    $dataStores = '';

                    foreach ($datastoreArr as $datastores) {

                        //$datastoreVal = $WgsVmwareObj->vmware_object_to_array($vms->datastoreDetail($datastores));

                        //$dataStores .= '<option value="' . $datastoreVal['RetrievePropertiesResponse']['returnval']['propSet']['val'] . '">' . $datastoreVal['RetrievePropertiesResponse']['returnval']['propSet']['val'] . '</option>';

                        $dataStores .= '<option value="' . $vms->datastoreDetail($datastores)->name . '">' . $vms->datastoreDetail($datastores)->name . '</option>';
                    }
                } else {

                    $dataStores = 'Not Found';
                }

                print_r(json_encode(array('datastore' => $dataStores)));
            } catch (Exception $ex) {

                print_r(json_encode(array('error' => $ex->getMessage())));
            }



            exit();

            break;

        case 'get_server_status':

            if (file_exists(__DIR__ . '/soap_class.php'))

                include_once __DIR__ . '/soap_class.php';



            $ssQuery = Capsule::table('mod_vmware_server')->where('id', filter_var($_POST['serverid'], FILTER_SANITIZE_STRING))->get();



            $ssQuery = (array) $ssQuery[0];

            $context = stream_context_create(array(

                'ssl' => array(

                    'verify_peer' => false,

                    'verify_peer_name' => false,

                    'allow_self_signed' => true

                )

            ));



            $clientcon = true;

            $connection = true;

            try {

                $client = new soapclientd($ssQuery['vsphereip'] . '/sdk/vimService.wsdl', array('location' => $ssQuery['vsphereip'] . '/sdk', 'trace' => 1, 'stream_context' => $context));
            } catch (Exception $e) {



                $clientcon = false;

                $connection = false;
            }



            if ($clientcon == true) {

                try {

                    $request = new stdClass();

                    $request->_this = array('_' => 'ServiceInstance', 'type' => 'ServiceInstance');

                    $response = $client->__soapCall('RetrieveServiceContent', array((array) $request));
                } catch (Exception $e) {

                    $connection = false;
                }

                $ret = $response->returnval;

                try {

                    $request = new stdClass();

                    $request->_this = $ret->sessionManager;

                    $request->userName = $ssQuery['vsphereusername'];

                    $decryptPw = $vmWare->vmwarePwEncryptDcrypt($ssQuery['vspherepassword']);

                    if ($decryptPw['result'] != 'success') {

                        $msg = $decryptPw['message'];

                        echo "<span class='label closed'>" . $msg . "</span>";

                        exit();
                    }

                    $request->password = html_entity_decode($decryptPw['password']);

                    $response = $client->__soapCall('Login', array((array) $request));
                } catch (Exception $e) {



                    $connection = false;
                }
            }

            if ($clientcon && $connection)

                echo "<span class='label active'>Connected</span>";

            else

                echo "<span class='label closed'>Not connected</span>";

            exit();

            break;

        case "getallhostname":

            $hosts = $vms->getAllHosts();

            $option = '';

            if (count($hosts) > 0 && !empty($hosts)) {



                foreach ($hosts as $host) {

                    if ($host->reference->_ == filter_var($_POST['postHost'], FILTER_SANITIZE_STRING))

                        $select = 'selected="selected"';

                    else

                        $select = '';

                    $option .= '<option value="' . $host->reference->_ . '" ' . $select . '>' . $host->name . '</option>';
                }
            } else {

                $option .= '<option value="">Not Found.</option>';
            }

            echo $option;

            exit();

            break;

        case "gethostname":

            $hostid = filter_var($_POST['hostid'], FILTER_SANITIZE_STRING);

            $host = $vms->get_host_parent($hostid);

            echo $host_obj = $host->name;

            exit();

            break;

        case "getvmBw":

            $vmId = filter_var($_POST['vmid'], FILTER_SANITIZE_STRING);

            $vmName = filter_var($_POST['vmname'], FILTER_SANITIZE_STRING);

            include_once __DIR__ . '/bw_counter.php';

            //            $host = $vms->get_host_parent($hostid);

            //            echo $host_obj = $host->name;

            exit();

            break;
        case "getallsettindc":
            if (!empty($serverid)) {
                try {
                    $error = false;
                    $datacenter = $vms->list_datacenters();
                    if (is_array($datacenter) && !empty($datacenter)) {
                        $error = false;
                    } else {
                        $error = "Datacenter not found";
                    }
                } catch (Exception $ex) {
                    $error = "Error: {$ex->getMessage()}";
                }
            } else {
                $error = "vCenter Server Id Not Found";
            }
            include_once __DIR__ . '/dc_host_setting.php';
            exit();
            break;
        case "getallsettindchost":
            try {

                
                $get_datacenterHosts = $vms->list_datacenters_host(filter_var($_POST['dcName'], FILTER_SANITIZE_STRING));
                $dcid = filter_var($_POST['dcid'], FILTER_SANITIZE_STRING);
                if ($get_datacenterHosts) {

                    $hostname = $resourcePool = '';
                    $host = [];
                    // foreach ($get_datacenter as $getDatacenterHostArr) {
                    //     if (count($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet']) > 0 && !empty($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'])) {
                    //         foreach ($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $datacenterList) {
                    //             if ($datacenterList['name'] == 'host') {
                    //                 if (count($datacenterList['val']['ManagedObjectReference']) > 1) {
                    //                     foreach($datacenterList['val']['ManagedObjectReference'] as $hostObj){
                    //                         $host[] = $hostObj;
                    //                     }
                    //                 } else {
                    //                     $host = array($datacenterList['val']['ManagedObjectReference']);
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    foreach($get_datacenterHosts as $hostKey => $hostRes){
                        foreach($hostRes as $hostData){
                            if($hostData)
                                $host[] = $hostData->reference->_;
                        }
                    }
                    if (!empty($host)) {
                        $html = '';
                        $html .= '<div class="panel-group" id="accordion' . $dcid . '">';
                        foreach ($host as $hostValue) {

                            $hostInfo = $vms->get_host_resources($hostValue);
                            $hostName = $hostInfo->getName();
                            $hostID = $hostInfo->reference->_;
                            $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverid)->where('dc_id', $dcid)->where('host_id', $hostID)->first();
                            $status = "<font color=\"green\">Enabled</font>";
                            if ($GetHostSetting->disable == 1)
                                $status = "<font color=\"red\">Disabled</font>";
                            $priorty = $GetHostSetting->priority;
                            $html .= '<div class="panel panel-default">';
                            $html .= '<div class="panel-heading">
                                    <h4 class="panel-title hosttitle">
                                        <a data-toggle="collapse" title="Host" data-parent="#accordion' . $dcid . '" href="#hostData' . $hostID . '" onclick="getHostSetting(this, \'' . $hostID . '\',  \'' . $hostName . '\',  \'' . $dcid . '\');">' . $hostName . '</a>
                                        <span style=" float: right;">Status: ' . $status . ', Prority:' . $priorty . '</span></h4>
                                </div>
                                <div id="hostData' . $hostID . '" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    <div class="col-sm-12 text-center" id="loader-' . $hostID . '"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
                                    <div id="hosthtml-' . $hostID . '"></div>
                                    </div>
                                </div>
                                </div>';
                        }
                        $html .= '</div>';
                    } else {
                        $html = 'Hosts Not Found!';
                    }
                } else {
                    $html = 'Records Not Found!';
                }
            } catch (Exception $ex) {

                $html = $ex->getMessage();
            }
            echo $html;
            exit();
            break;
        case "getallhostsetting":
            $dcid = $whmcs->get_req_var('dcid');
            $hostID = $whmcs->get_req_var('hostid');
            $hostInfo = $vms->get_host_resources($hostID);
            $hostName = $hostInfo->getName();
            $hostID = $hostInfo->reference->_;
            $html .= '<form id="form-' . $hostID . '"><div class="col-sm-12">';
            $hardWareInfo = $hostInfo->getHardware();
            $memorySize = vmwareAddon_formatSizeUnits($hardWareInfo->memorySize);
            $numCpuPackages = $hardWareInfo->cpuInfo->numCpuPackages;
            $numCpuCores = $hardWareInfo->cpuInfo->numCpuCores;
            $numCpuThreads = $hardWareInfo->cpuInfo->numCpuThreads;
            $hz = $hardWareInfo->cpuInfo->hz;
            $hls = $hostInfo->getSummary();
            $cpuMhz = $hls->hardware->cpuMhz;
            $cpuModel = $hls->hardware->cpuModel;
            $numNics = $hls->hardware->numNics;
            $powerState = $hls->runtime->powerState;
            $connectionState = $hls->runtime->connectionState;
            $overallCpuUsage = round($hls->quickStats->overallCpuUsage / 1000, 2);
            $cpuCapacity = round($numCpuCores * $cpuMhz / 1000, 2);
            $freeCpu = round($numCpuCores * $cpuMhz / 1000 - $hls->quickStats->overallCpuUsage / 1000, 2);

            $overallMemoryUsage = vmwareAddon_formatSizeUnits($hls->quickStats->overallMemoryUsage, true);
            $FreeMemory = $memorySize['rawdata'] - $overallMemoryUsage['rawdata'];
            $uptime = $hls->quickStats->uptime;
            $totalVm = count($hostInfo->getVm());
            $totalFreeSpaceRaw = $totalCapacityRaw = $totalUsed = 0;
            $dataStoreOptions = '';
            $serverid = $whmcs->get_req_var('serverid');
            $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverid)->where('dc_id', $dcid)->where('host_id', $hostID)->first();
            $priorityCheck = $disableCheck = '';
            foreach ($hostInfo->getDatastore() as $DS) {
                $dsID = $DS->reference->_;
                $GetDsSetting = Capsule::table("mod_vmware_ds_setting")->where('ds_id', $dsID)->where('host_id', $hostID)->first();
                $dsName = $DS->getName();
                $dsCapacity = vmwareAddon_formatSizeUnits($DS->getSummary()->capacity)['data'];
                $dsFreeSpace = vmwareAddon_formatSizeUnits($DS->getSummary()->freeSpace)['data'];
                $totalFreeSpaceRaw = $totalFreeSpaceRaw + $DS->getSummary()->freeSpace;
                $totalCapacityRaw = $totalCapacityRaw + $DS->getSummary()->capacity;
                $priorityCheck = $GetDsSetting->priority;
                $disableCheck = $GetDsSetting->disable != 0 ? 'checked' : '';
                $dataStoreOptions .= '<input type="hidden" id="dsobj" name="dsobj[]" value="' . $dsID . '"><div class="sub-heading"><span>' . $dsName . ' (' . $LANG['provisioning_capacity'] . ': ' . $dsCapacity . ', ' . $LANG['provisioning_freespace'] . ': ' . $dsFreeSpace . ')</span></div><div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="disable_ds_' . $dsID . '"><input type="checkbox" class="checkbox" id="disable_ds_' . $dsID . '" name="disable_ds[' . $hostID . '][' . $dsID . ']" ' . $disableCheck . '> ' . $LANG['provisioning_disable_datastore'] . '</label>

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="priority_ds_' . $dsID . '">' . $LANG['provisioning_priority'] . ':</label>
                                                    <input type="number" class="form-control" id="priority_ds_' . $dsID . '" name="priority_ds[' . $hostID . '][' . $dsID . ']" value="' . $priorityCheck . '">
                                                </div>
                                            </div>
                                </div>';
                //$dataStoreOptions .= '<label for="datastore-' . $dsID . '"><input type="radio" id="datastore-' . $dsID . '" name="datastore[' . $hostID . ']" value="' . $dsID . '">&nbsp; ' . $dsName . ' (Capacity: ' . $dsCapacity . ', Free Space: ' . $dsFreeSpace . ')</label><br/>';
            }
            $networkOptions = $nwSelect = '';
            foreach ($hostInfo->getNetwork() as $NW) {
                $nwID = $NW->reference->_;
                $nwName = $NW->getName();
                $nwSelect = $GetHostSetting->network == $nwID ? 'selected' : '';
                $networkOptions .= '<option value="' . $nwID . '" ' . $nwSelect . '>' . $nwName . '</option>';
            }
            $totalFreeSpace =  vmwareAddon_formatSizeUnits($totalFreeSpaceRaw)['data'];
            $totalCapacity =  vmwareAddon_formatSizeUnits($totalCapacityRaw)['data'];
            $totalUsed = vmwareAddon_formatSizeUnits($totalCapacityRaw - $totalFreeSpaceRaw)['data'];
            $priorityCheck = $GetHostSetting->priority;
            $vmDbQty = $GetHostSetting->vm_num;
            $disableCheck = $GetHostSetting->disable != 0 ? 'checked' : '';
            $html .= '<div class="hostname-contianer"><!--div class="hostname"><h2><label>' . $LANG['provisioning_host'] . ': ' . $hostName . '</label></h2></div-->';
            $html .= '<div class="col-sm-8"><div class="sub-heading"><span>' . $LANG['provisioning_esxi_host_setting'] . '</span></div><div class="row">';
            $html .= '<input type="hidden" id="hostobj" name="hostobj" value="' . $hostID . '">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="disable_esxi">
                                                <input type="checkbox" class="checkbox" id="disable_esxi" name="disable_esxi[' . $hostID . ']" ' . $disableCheck . '> ' . $LANG['provisioning_disable_host'] . '</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="network">' . $LANG['provisioning_select_nw'] . ':</label>
                                                <select name="network[' . $hostID . ']" class="form-control" id="network">
                                                    ' . $networkOptions . '
                                                </select>
                                            </div>
                                        </div>';
            $html .= '<div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="priority">' . $LANG['provisioning_priority'] . ':</label>
                                                <input type="number" class="form-control" id="priority" name="priority[' . $hostID . ']" min="0" value="' . $priorityCheck . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="vmqty">' . $LANG['provisioning_no_of_vms'] . ':</label>
                                                <input type="number" class="form-control" id="vmqty" name="vmqty[' . $hostID . ']" min="0" value="' . $vmDbQty . '">
                                            </div>
                                        </div>
                                        </div><!--Row Close-->';
            /*$html .= '<div class="sub-heading"><span>Datastore Setting</span></div><div class="row"><div class="col-sm-6">
                                            <div class="form-group">
                                                ' . $dataStoreOptions . '
                                            </div>
                                        </div></div>';*/
            $html .= $dataStoreOptions;
            $html .= '</div><div class="col-sm-4">';
            $html .= '<p><label>' . $LANG['provisioning_host'] . ':</label> ' . ucfirst($hostName) . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_state'] . ':</label> ' . ucfirst($connectionState) . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_processor_type'] . ':</label> ' . $cpuModel . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_logically_processor'] . ':</label> ' . $numCpuThreads . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_cpu'] . ':</label> ' . $numCpuCores . ' CPUs x ' . round($cpuMhz / 1000, 2)  . ' GHz</p>';
            $html .= '<p><label>' . $LANG['provisioning_nics'] . ':</label> ' . $numNics . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_virtual_machines'] . ':</label> ' . $totalVm . '</p>';
            $html .= '<p><label>' . $LANG['provisioning_cpu'] . ':</label> <br/>' . $LANG['provisioning_capacity'] . ': ' . $cpuCapacity . 'GHz<br/>' . $LANG['provisioning_used'] . ': ' . $overallCpuUsage . 'GHz<br/>' . $LANG['provisioning_free'] . ': ' . $freeCpu . 'GHz</p>';
            $html .= '<p><label>' . $LANG['provisioning_memory'] . ':</label> <br/>' . $LANG['provisioning_capacity'] . ': ' . $memorySize['data'] . '<br/>' . $LANG['provisioning_used'] . ': ' . $overallMemoryUsage['data'] . '<br/>' . $LANG['provisioning_free'] . ': ' . $FreeMemory . ' GB</p>';
            $html .= '<p><label>' . $LANG['provisioning_storage'] . ':</label> <br/>' . $LANG['provisioning_capacity'] . ': ' . $totalCapacity . '<br/>' . $LANG['provisioning_used'] . ': ' . $totalUsed . '<br/>' . $LANG['provisioning_free'] . ': ' . $totalFreeSpace . '</p>';
            $html .= '</div><div class="col-sm-12 text-center">
                            <button type="button" id="btn-' . $dcid . '" class="dc_btn btn btn-default" onclick="saveHostSetting(this, \'' . $hostID . '\', \'' . $dcid . '\')">' . $LANG['provisioning_submit'] . '</button>
                        </div></div>';
            echo $html .= '</div></form></div>';
            exit();
            break;
        case "saveHostSetting":
            global $whmcs;
            $dcid = $whmcs->get_req_var('dcid');
            $hostid = $whmcs->get_req_var('hostobj');
            $serverid = $whmcs->get_req_var('serverid');
            $disable_esxi = $whmcs->get_req_var('disable_esxi')[$hostid];
            $network = $whmcs->get_req_var('network')[$hostid];
            $priority = $whmcs->get_req_var('priority')[$hostid];
            $vmqty = $whmcs->get_req_var('vmqty')[$hostid];
            if ($disable_esxi == 'on')
                $disable_esxi = 1;
            else
                $disable_esxi = 0;
            $uniqid = uniqid();
            try {
                $data = [
                    "dc_id" => $dcid,
                    "host_id" => $hostid,
                    "network" => $network,
                    "serverid" => $serverid,
                    "disable" => $disable_esxi,
                    "priority" => $priority,
                    "vm_num" => $vmqty,
                ];
                if (Capsule::table("mod_vmware_host_setting")->where('serverid', $serverid)->where('dc_id', $dcid)->where('host_id', $hostid)->count() == 0) {
                    Capsule::table("mod_vmware_host_setting")->insert($data);
                } else {
                    Capsule::table("mod_vmware_host_setting")->where('serverid', $serverid)->where('dc_id', $dcid)->where('host_id', $hostid)->update($data);
                }

                foreach ($whmcs->get_req_var('dsobj') as $ds) {
                    $disable_ds = $whmcs->get_req_var('disable_ds')[$hostid][$ds];
                    $priority_ds = $whmcs->get_req_var('priority_ds')[$hostid][$ds];
                    if ($disable_ds == 'on')
                        $disable_ds = 1;
                    else
                        $disable_ds = 0;
                    $data = [
                        "ds_id" => $ds,
                        "host_id" => $hostid,
                        "disable" => $disable_ds,
                        "priority" => $priority_ds,
                    ];
                    if (Capsule::table("mod_vmware_ds_setting")->where('ds_id', $ds)->where('host_id', $hostid)->count() == 0) {
                        Capsule::table("mod_vmware_ds_setting")->insert($data);
                    } else {
                        Capsule::table("mod_vmware_ds_setting")->where('ds_id', $ds)->where('host_id', $hostid)->update($data);
                    }
                }
                $result = ['status' => 'success', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-notice growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Success!</div><div class="growl-message">Successfully Updated.</div></div>'];
            } catch (Exception $ex) {
                $result = ['status' => 'error', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-error growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Error!</div><div class="growl-message">' . $ex->getMessage() . '</div></div>'];
            }
            print json_encode($result);
            exit();
        case "updaterdns":
            $uniqid = uniqid();
            try {
                Capsule::table("mod_vmware_ip_list")->where("id", $whmcs->get_req_var('id'))->update(["reversedns" => $whmcs->get_req_var('rdns')]);
                $result = ['status' => 'success', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-notice growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Success!</div><div class="growl-message">Successfully Updated.</div></div>'];
            } catch (Exception $ex) {
                $result = ['status' => 'error', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-error growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Error!</div><div class="growl-message">' . $ex->getMessage() . '</div></div>'];
            }
            print json_encode($result);
            exit();
            break;
        case "updaterdesc":
            $uniqid = uniqid();
            try {
                Capsule::table("mod_vmware_ip_list")->where("id", $whmcs->get_req_var('id'))->update(["desc" => $whmcs->get_req_var('desc')]);
                $result = ['status' => 'success', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-notice growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Success!</div><div class="growl-message">Successfully Updated.</div></div>'];
            } catch (Exception $ex) {
                $result = ['status' => 'error', 'uniqid' => $uniqid, 'message' => '<div class="growl growl-error growl-medium ' . $uniqid . '"><div class="growl-close">×</div><div class="growl-title">Error!</div><div class="growl-message">' . $ex->getMessage() . '</div></div>'];
            }
            print json_encode($result);
            exit();
            break;
    }
}



function getWHMCSVmList()
{

    $vmArr = array();

    foreach (Capsule::table('tblhosting')->select('id', 'userid', 'packageid')->where('domainstatus', 'Active')->get() as $result) {

        $result = (array) $result;



        $productDetails = Capsule::table('tblproducts')->where('id', $result['packageid'])->get();

        $productDetails = (array) $productDetails[0];

        if ($productDetails['servertype'] == 'vmware') {

            $customFieldVal = vmwareGetCustomFiledVal($productDetails, true);



            $getCustomfieldId = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $result['packageid'])->where('fieldname', 'like', '%' . $customFieldVal['vm_name_field'] . '%')->get();

            $getvmname = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $getCustomfieldId[0]->id)->where('relid', $result['id'])->get();

            $getvmname = (array) $getvmname[0];

            $vmArr[] = $getvmname['value'];
        }
    }

    return $vmArr;
}


function vmwareAddon_formatSizeUnits($bytes, $unit = null)
{
    if ($unit) {
        if ($bytes >= 1024) {
            $result['data'] = number_format($bytes / 1024, 2) . ' GB';
            $result['rawdata'] = number_format($bytes / 1024, 2);
        } elseif ($bytes < 1024) {
            $result['data'] = 1 . ' MB';
            $result['rawdata'] = 1;
        }
    } else {
        if ($bytes >= 1099511627776) {
            $result['data'] = number_format($bytes / 1099511627776, 2) . ' TB';
            $result['rawdata'] = number_format($bytes / 1099511627776, 2);
        } else if ($bytes >= 1073741824) {
            $result['data'] = number_format($bytes / 1073741824, 2) . ' GB';
            $result['rawdata'] = number_format($bytes / 1073741824, 2);
        } elseif ($bytes >= 1048576) {
            $result['data'] = number_format($bytes / 1048576, 2) . ' MB';
            $result['rawdata'] = number_format($bytes / 1048576, 2);
        } elseif ($bytes >= 1024) {
            $result['data'] = number_format($bytes / 1024, 2) . ' KB';
            $result['rawdata'] = number_format($bytes / 1024, 2);
        } elseif ($bytes > 1) {
            $result['data'] = $bytes . ' bytes';
            $result['rawdata'] = $bytes;
        } elseif ($bytes == 1) {
            $result['data'] = $bytes . ' byte';
            $result['rawdata'] = $bytes;
        } else {
            $result['data'] = '0 bytes';
            $result['rawdata'] = 0;
        }
    }
    return $result;
}
