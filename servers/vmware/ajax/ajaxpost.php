<?php

global $whmcs;

use Illuminate\Database\Capsule\Manager as Capsule;
use phpseclib\Net\SSH2;

$custom = filter_var($_POST['custom'], FILTER_SANITIZE_STRING);
if (!empty($custom)) {
    if (file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/init.php'))
        require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/init.php';
}
$get = filter_var($_POST['get'], FILTER_SANITIZE_STRING);

$os_list = filter_var($_POST['os_list'], FILTER_SANITIZE_STRING);

$configurableoption = filter_var($_POST['configurableoption'], FILTER_SANITIZE_STRING);

$server = filter_var($_POST['server'], FILTER_SANITIZE_STRING);

$configoptionid = filter_var($_POST['configoptionid'], FILTER_SANITIZE_STRING);


if (!empty($custom) && $get == 'hostlist') {
    require_once dirname(__DIR__) . '/class/class.php';
    try {

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files();

        $server_name = $whmcs->get_req_var("server_name");

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $server_name)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $server_name)->get();
        if (count($serverData) > 0) {

            $productid = $whmcs->get_req_var("productid");

            $getProductQuery = Capsule::table('tblproducts')->where('id', $productid)->get();

            $getProductDetail = (array) $getProductQuery[0];

            $ExistHostsName = $getProductDetail['configoption15'];

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);
            if ('none' == $ExistHostsName)
                $sel = 'selected="selected"';
            else
                $sel = '';
            $hostOption = '<option ' . $sel . ' value="none">None</option>';

            $hosts = $vms->getAllHosts();
            if ($hosts) {

                foreach ($hosts as $hostsVal) {

                    $hostName = $hostsVal->name;

                    if ($hostsVal->reference->_ == $ExistHostsName)
                        $sel = 'selected="selected"';
                    else
                        $sel = '';

                    $hostOption .= '<option ' . $sel . ' value="' . $hostsVal->reference->_ . '">' . $hostName . '</option>';
                }

                print_r(json_encode(array('status' => 'success', 'type' => 'success', 'option' => $hostOption)));
            } else {

                $hostOption = '<option value="" selected disabled>Not Found</option>';

                print_r(json_encode(array('status' => 'success', 'type' => 'error', 'option' => $hostOption)));
            }
        } else {

            $hostOption = '<option value="" selected disabled>Not Found</option>';

            print_r(json_encode(array('status' => 'success', 'type' => 'error', 'option' => $hostOption)));
        }
    } catch (Exception $ex) {

        print_r(json_encode(array('status' => 'error', 'type' => 'error', 'msg' => $ex->getMessage())));
    }
    exit();
}
if (!empty($custom) && $get == 'vmware_host_datastores') {
    require_once dirname(__DIR__) . '/class/class.php';
    try {

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files();

        $server_name = $whmcs->get_req_var("server_name");
        $defaultHost=$whmcs->get_req_var("hostid");
        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $server_name)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $server_name)->get();


        if (count($serverData) > 0 && !empty($defaultHost)) {

            $productid = $whmcs->get_req_var("productid");

            $getProductQuery = Capsule::table('tblproducts')->where('id', $productid)->get();

            $getProductDetail = (array) $getProductQuery[0];

            $ExistHostsName = $getProductDetail['configoption15'];

            $getip = explode('://', $serverData[0]->vsphereip);

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);
            $hasssd=$hasnvme=false;
            $configoptid=Capsule::table('tblproductconfigoptions')->leftJoin("tblproductconfiglinks","tblproductconfigoptions.gid","=","tblproductconfiglinks.gid")->where(['tblproductconfigoptions.optionname'=>'storagetype','tblproductconfiglinks.pid'=>$productid])->value("tblproductconfigoptions.id");
                if($configoptid){
                   $hasssd=$hasnvme=false;
                   $opts= Capsule::table('tblproductconfigoptionssub')->where(['configid'=>$configoptid])->get();
                   if(count($opts)){
                        foreach ($opts as $key => $opt) {
                            if(stripos($opt->optionname, "NVMe")!==false){
                                $hasnvme=true;
                            }
                            if(stripos($opt->optionname, "SSD")!==false){
                                 $hasssd=true;
                            }
                        }
                    }

                }


            if ('none' == $ExistHostsName)
                $sel = 'selected="selected"';
            else
                $sel = '';

            $hostOption = '<option value="" selected disabled>Not Found</option>';

            $response=['status' => 'success', 'type' => 'error', 'option' => $hostOption];



            $hostOption = '';


            if ($defaultHost != 'none') {
                if ($vms->get_host_network($defaultHost)) {

                        $GetDsSetting = Capsule::table("mod_vmware_ds_setting")->where('host_id', $defaultHost)->where('disable', '0')->orderBy('priority', 'ASC')->get();
                        foreach ($GetDsSetting as $k=>$DS) {
                            $DsInfo = $vms->datastoreDetail($DS->ds_id);
                            $dsFreeDisk = round($DsInfo->getSummary()->freeSpace / 1073741824, 2);
                            if ((float) $hardDisk < (float) $dsFreeDisk) {
                                $datastore_id = $DS->ds_id;
                                if(empty($sel)){
                                    if($k==0 && !$hasnvme){
                                        $sel = 'selected="selected"';
                                    }
                                }
                                if($hasnvme && stripos($DsInfo->getSummary()->name, "NVMe")!==false){
                                     $sel = 'selected="selected"';
                                }
                                $hostOption .= '<option ' . $sel . ' value="' . $DsInfo->getSummary()->name."|".$datastore_id. '">' . $DsInfo->getSummary()->name . '</option>';

                                $sel = '';
                            }
                        }
                        $response=['status' => 'success', 'type' => 'success', 'option' => $hostOption];
                }
            }


        } else {


            $response=['status' => 'error', 'type' => 'error', 'msg' => "no options available"];
        }
    } catch (Exception $ex) {

        $response=['status' => 'error', 'type' => 'error', 'msg' => $ex->getMessage()];
    }
    echo json_encode($response);
    exit();
}
if (!empty($custom) && $get == 'configDetail') {

    require_once dirname(__DIR__) . '/class/class.php';
    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files();

    $serverData = Capsule::table('mod_vmware_server')->where('server_name', $server)->get();
    if (count($serverData) == 0)
        $serverData = Capsule::table('mod_vmware_server')->where('id', $server)->get();

    $getip = explode('://', $serverData[0]->vsphereip);

    $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

    $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

    try {

        $networks = $vms->list_networks();

        $network_list = $WgsVmwareObj->vmware_object_to_array($networks[0]);

        $network_adeptor_list = '';

        foreach ($network_list['RetrievePropertiesResponse']['returnval'] as $key => $networkAdeptors) {

            if ($key == 'obj' && $key != '0') {

                $network_adeptor_list .= '<option value="' . $networkAdeptors . '">' . $networkAdeptors . '</option>';
            } else if ($key == 'propSet' && $key != '0') {
            } else {

                $network_adeptor_list .= '<option value="' . $networkAdeptors['obj'] . '">' . $networkAdeptors['obj'] . '</option>';
            }
        }
    } catch (Exception $ex) {

        logActivity("Error in get netadeptor Error: " . $ex->getMessage());
    }

    try {

        $datastoresoption = '';

        $datastores = $vms->list_datastores();

        $datastoreIds = $WgsVmwareObj->vmware_object_to_array($datastores[0]);

        if (isset($datastoreIds['RetrievePropertiesResponse']['returnval'])) {

            if (isset($datastoreIds['RetrievePropertiesResponse']['returnval'][1])) {

                foreach ($datastoreIds['RetrievePropertiesResponse']['returnval'] as $datastoresValue) {

                    $datastoresoption .= '<option value="' . $datastoresValue['propSet'][0]['val'] . '">' . $datastoresValue['propSet'][0]['val'] . '</option>';
                }
            } else {

                $datastoresoption = '<option value="' . $datastoresValue['propSet'][0]['val'] . '">' . $datastoreIds['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'] . '</option>';
            }
        }
    } catch (Exception $ex) {

        logActivity("Error in get datastore Error: " . $ex->__toString());
    }
    try {

        $resoucepoolName = '';

        $resoucepools = $vms->list_resouce_pools();

        $resoucepoolIds = $WgsVmwareObj->vmware_object_to_array($resoucepools[0]);

        if (isset($resoucepoolIds['RetrievePropertiesResponse']['returnval'])) {

            foreach ($resoucepoolIds['RetrievePropertiesResponse']['returnval'] as $key => $resoucepoolValue) {

                if ($key == 'obj' && $key != '0') {

                    $resoucepoolName .= '<option value="' . $resoucepoolValue . '">' . $resoucepoolValue . '</option>';
                } else if ($key == 'propSet' && $key != '0') {
                } else {

                    $resoucepoolName .= '<option value="' . $resoucepoolValue['obj'] . '">' . $resoucepoolValue['obj'] . '</option>';
                }
            }
        }
    } catch (Exception $ex) {

        logActivity("Error in get resourcepool Error: " . $ex->__toString());
    }

    print_r(json_encode(array('networkAdeptor' => $network_adeptor_list, 'datastore' => $datastoresoption, 'resourcepool' => $resoucepoolName)));

    exit();
}

if (!empty($custom) && !empty($os_list)) {

    require_once dirname(__DIR__) . '/class/class.php';

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files();

    $os_option = '';

    $os_version = '';

    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);

    $productResult = Capsule::table('tblproducts')->where('id', $pid)->first();

    $productResult = (array) $productResult;

    $params = $productResult;

    $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');

    $serviceid = filter_var($_POST['serviceid'], FILTER_SANITIZE_STRING);

    $customFiledID = Capsule::table('tblcustomfields')->select('id')->where('type', 'product')->where('relid', $pid)->where('fieldname', 'like', '%' . $customFieldVal['os_version_field'] . '%')->get();

    if ($serviceid)
        $productQuery = Capsule::table('tblcustomfieldsvalues')->select('value')->where('fieldid', $customFiledID[0]->id)->where('relid', $serviceid)->get();

    $getServerIdArr = Capsule::table('mod_vmware_server')->where('server_name', $params['configoption3'])->first();
    if (count($getServerIdArr) == 0)
        $getServerIdArr = Capsule::table('mod_vmware_server')->where('id', $params['configoption3'])->first();

    $getServerId = $getServerIdArr->id;
    $dc = "";

    if ($params['configoption15'] != "none") {
        $vms = new vmware();
        $getip = explode('://', $getServerIdArr->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($getServerIdArr->vspherepassword);

        $vms = new vmware($getip[1], $getServerIdArr->vsphereusername, $decryptPw);

        $hostsystem_name = $vms->get_host_parent($params['configoption15'])->name;
    }
    $dc = filter_var($_POST['dc'], FILTER_SANITIZE_STRING);

    $dcArr = explode('|', $dc);
    $dc = trim($dcArr[0]);
    if (!empty($configurableoption)) {
        $getConfigurableOptionOs = Capsule::table('tblproductconfigoptionssub')->where('id', $os_list)->first();
        $osArr = explode('|', $getConfigurableOptionOs->optionname);
        $os_list = $osArr[0];
    }

    $cloneOsListQryArr = array();

    $iso_osListQryArr = array();
 
    $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where('datacenter', $dc)->get();   
    if(count($cloneOsListQry) == 0)
        $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->get();
    if ($hostsystem_name != "")
        $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where("hostname", $hostsystem_name)->where('datacenter', $dc)->get();

    $i = 0;

    foreach ($cloneOsListQry as $key => $os_lists) {

        $cloneOsListQryArr[$key] = $os_lists->customname;
    }

    $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where('datacenter', $dc)->get();
    if(count($iso_osListQry) == 0)
    $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->get();
    if ($hostsystem_name != "")
        $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where("hostname", $hostsystem_name)->where('datacenter', $dc)->get();

    foreach ($iso_osListQry as $key => $os_list) {

        $iso_osListQryArr[$key] = $os_list->os_version;
    }

    $osListQry = array_unique(array_merge($cloneOsListQryArr, $iso_osListQryArr));

    if (empty($configurableoption)) {

        foreach ($osListQry as $os_list) {

            if ($productQuery[0]->value == $os_list)
                $sel = 'selected="selected"';
            else
                $sel = '';

            $os_option .= '<option ' . $sel . ' value="' . $os_list . '">' . $os_list . '</option>';

            $os_version .= $os_list . ',';
        }

       $os_version = rtrim($os_version, ',');

        try {

            $updatedUserCount = Capsule::table('tblcustomfields')
                ->where('id', $customFiledID[0]->id)
                ->update(
                    [
                        'fieldoptions' => $os_version,
                    ]
                );
        } catch (\Exception $e) {
        }
    } else {

        $i = filter_var($_POST['i'], FILTER_SANITIZE_STRING);
        $getExistingConfigOptionId = Capsule::table('tblhostingconfigoptions')->where('relid', $whmcs->get_req_var('sid'))->where('configid', $configoptionid)->first();
        foreach ($osListQry as $os_list) {

            $getConfigurableSubOption = Capsule::table('tblproductconfigoptionssub')->where('configid', $configoptionid)->where('optionname', $os_list)->where('hidden', '0')->get();

            foreach ($getConfigurableSubOption as $configurableSubOption) {

                $configurableSubOption = (array) $configurableSubOption;

                if ($configurableSubOption['id'] == $_SESSION['cart']['products'][$i]['configoptions'][$configoptionid] || $getExistingConfigOptionId->optionid == $configurableSubOption['id'])
                    $sel = 'selected="selected"';
                else
                    $sel = '';

                $os_option .= '<option value="' . $configurableSubOption['id'] . '" ' . $sel . '>' . $configurableSubOption['optionname'] . '</option>';
            }
        }
    }

    echo $os_option;

    exit();
}

if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {

    if (!empty($custom) && !empty($get) && $get == 'additionalipstatus') {

        $pid = $whmcs->get_req_var("pid");

        $serviceid = $whmcs->get_req_var("serviceid");

        $admin = Capsule::table('tbladmins')->select('id')->get();

        $command = "getclientsproducts";

        $adminuser = $admin[0]->id;

        $values["serviceid"] = $serviceid;

        $results = localAPI($command, $values, $adminuser);

        $getProductDetail = Capsule::table('tblproducts')->where('id', $pid)->get();

        $params = (array) $getProductDetail[0];

        require_once dirname(__DIR__) . '/class/class.php';

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files();

        $customFieldVal = vmwareGetCustomFiledVal($params, 'hook');

        $datacenterName = $customFieldVal['datacenter_field'];

        $additional_ip_field = $customFieldVal['additional_ip_field'];

        $getConfigOptionDetail = Capsule::table('tblproductconfigoptions')->where('optionname', 'like', '%' . $additional_ip_field . '%')->get();

        $configoption = $whmcs->get_req_var("configoption");

        foreach ($getConfigOptionDetail as $dbCVal) {

            $dbCVal = (array) $dbCVal;
            foreach ($configoption as $cKey => $cVal) {
                if ($cKey == $dbCVal['id']) {
                    $additionalIp = $cVal;
                    if($dbCVal['optiontype'] == 1){
                        $getSubOptionVal = Capsule::table('tblproductconfigoptionssub')->where('id', $cVal)->first();
                        $subValArr = explode("|", $getSubOptionVal->optionname);
                        $additionalIp = $subValArr[0];
                    }
                    $qtyMaximum = $dbCVal['qtymaximum'];
                }
            }
        }

        if (!empty($additionalIp) && $additionalIp > 0) {
            $dcCfId = Capsule::table("tblcustomfields")->where('type', 'product')->where('relid', $pid)->where('fieldname', 'LIKE', '%' . $datacenterName . '%')->first();

            $fieldoptionsArr = explode(',', $dcCfId->fieldoptions);

            foreach ($results['products']['product'][0]['customfields']['customfield'] as $cfKey => $cfDetail) {

                if ($cfDetail['id'] == $dcCfId->id) {
                    $datacenter = $cfDetail['value'];
                }
            }
            foreach ($fieldoptionsArr as $fieldOption) {
                $optionArr = explode('|', $fieldOption);
                if ($optionArr[1] == $datacenter)
                    $datacenter = $optionArr[0];
            }
            $ipListArr = array();
            foreach (Capsule::table('mod_vmware_ip_list')->where('datacenter', $datacenter)->where('status', '0')->inRandomOrder()->limit($additionalIp)->get() as $ipList) {

                $ipList = (array) $ipList;

                $ipListArr[] = $ipList;
            }

            if ($additionalIp > count($ipListArr)) {

                $result = 'Available IP ' . count($ipListArr) . ',  please decrease the IP or contact to support';
            } else {

                $result = 'success';
            }
        } else {

            $result = 'success';
        }

        echo $result;

        exit();
    }

    //soft reboot

    $ajaxaction = $whmcs->get_req_var("ajaxaction");

    if ($ajaxaction == 'softreboot') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_reboot($new_vm_name);

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $success = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $_LANG['vm_reboot_msg'] . '<div>';

                $description = " VM Succefully Soft Rebooted. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $description = "VM Soft Reboot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$response_obj['state']}";

                $status = "Failed";

                $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $response_obj['state'] . '<div>';
            }

            logModuleCall("VMware", "Soft reboot", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));
        } catch (Exception $e) {

            $description = "VM Soft Reboot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$e->getMessage()}";

            $status = "Failed";

            $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $e->getMessage() . '<div>';

            logModuleCall("VMware", "Soft reboot", array('name' => $new_vm_name), $e->getMessage());
        }

        if (!empty($error)) {

            echo $error;
        } else {

            echo $success;
        }

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        exit();
    }

    //hard reboot
    if ($ajaxaction == 'hardreboot') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_reset($new_vm_name);

            logModuleCall("VMware", "hard reboot", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $success = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $_LANG['vm_reboot_msg'] . '<div>';

                $description = " VM Succefully Hard Rebooted. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $response_obj['state'] . '<div>';

                $description = "VM Hard Reboot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$response_obj['state']}";

                $status = "Failed";
            }
        } catch (Exception $e) {

            $description = "VM Hard Reboot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$e->getMessage()}";

            $status = "Failed";

            $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $e->getMessage() . '<div>';

            logModuleCall("VMware", "hard reboot", array('name' => $new_vm_name), $e->getMessage());
        }

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        if (!empty($error))
            echo $error;
        else
            echo $success;

        exit();
    }

    //poweroff
    if ($ajaxaction == 'poweroff') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_power_off($new_vm_name);

            logModuleCall("VMware", "poweroff vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Power Off (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Power Off Failed (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Power Off Failed (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        if (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOn') {

            $btnStatus = 'poweredOn';

            $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

            $btn = $_LANG['vm_powered_off'];
        } elseif (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOff') {

            $btnStatus = 'poweredOff';

            $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

            $btn = $_LANG['vm_powered_on'];
        } else {

            $btnStatus = $Status = $whmcs->get_req_var("status");

            $btn = $whmcs->get_req_var("btn");
        }

        $class = $whmcs->get_req_var("class");

        if (empty($class)) {

            $runtime = '';

            $res = array('runtime' => $runtime, 'statusmsg' => $_LANG['vm_power_off_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="power_button_action(this,\'' . $btn . '\', \'' . $new_vm_name . '\',\'' . $btnStatus . '\',\'' . $_LANG['vm_power_off_alert_msg'] . '\');" class="reb_btn">' . $btn . '</a>');
        } else {

            $runtime = '';

            $res = array('runtime' => $runtime, 'status' => $Status, 'message' => $result, 'statusmsg' => $_LANG['vm_power_off_msg']);
        }

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }

    //poweron

    if ($ajaxaction == 'poweron') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_power_on($new_vm_name);

            logModuleCall("VMware", "poweron vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Power On (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Power On (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }

            if (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOn') {

                $btnStatus = 'poweredOn';

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $_LANG['vm_powered_off'];
            } elseif (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOff') {

                $btnStatus = 'poweredOff';

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $_LANG['vm_powered_on'];
            } else {

                $btnStatus = $Status = $whmcs->get_req_var("status");

                $btn = $whmcs->get_req_var("btn");
            }
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Power On (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        $class = $whmcs->get_req_var("class");

        if (empty($class)) {

            try {

                $runtime = '<div class="tab_row"><strong>System uptime</strong><span> ' . round($vms->get_vm_info($new_vm_name)->summary->quickStats->uptimeSeconds) . ' hours&nbsp;</span></div>';
            } catch (Exception $ex) {

                $result = $ex->getMessage();

                $description = "VM Power On (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }

            $res = array('runtime' => $runtime, 'statusmsg' => $_LANG['vm_power_on_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="power_button_action(this,\'' . $btn . '\', \'' . $new_vm_name . '\',\'' . $btnStatus . '\',\'' . $_LANG['vm_power_off_alert_msg'] . '\');" class="reb_btn">' . $btn . '</a>');
        } else {

            $runtime = '';

            $res = array('runtime' => $runtime, 'status' => $Status, 'message' => $result, 'statusmsg' => $_LANG['vm_power_on_msg']);
        }

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }

    //mount

    if ($ajaxaction == 'mount') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState != 'poweredOn') {

                $vms->vm_power_on($new_vm_name);
            }

            $response_obj = $vms->vm_mount($new_vm_name);

            logModuleCall("VMware", "mount vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Mounted. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Mount Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Mount Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        $mountStatus = vmwareGetMountStatus($vms, $new_vm_name, 'true');

        if ($result == 'success') {

            if ($mountStatus == 'true') {

                $btnStatus = 'true';

                $Status = $mountStatus;

                $btn = $_LANG['vm_unmount'];
            } elseif ($mountStatus == 'false') {

                $btnStatus = 'false';

                $Status = $mountStatus;

                $btn = $_LANG['vm_mount'];
            } else {

                $btnStatus = $Status = $whmcs->get_req_var("status");

                $btn = $whmcs->get_req_var("btn");
            }
        } else {

            $btnStatus = $Status = $whmcs->get_req_var("status");

            $btn = $whmcs->get_req_var("btn");
        }

        global $CONFIG;

        $system_url = $CONFIG['SystemURL'];

        if ($CONFIG['SystemSSLURL'] != '') {

            $system_url = $CONFIG['SystemSSLURL'];
        }

        $res = array('action' => $ajaxaction, 'console_link' => $system_url . '/clientarea.php?action=productdetails&id=' . $params['serviceid'] . '&modop=custom&a=vmconsole', 'install_msg' => $_LANG['vm_install_msg'], 'statusmsg' => $_LANG['vm_mount_on_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="mount_button_action(this,\'' . $btn . '\', \'' . $new_vm_name . '\',\'' . $btnStatus . '\');" class="reb_btn">' . $btn . '</a>');



        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }

    //mount
    if ($ajaxaction == 'unmount') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState != 'poweredOn') {

                $vms->vm_power_on($new_vm_name);
            }

            $response_obj = $vms->vm_unmount($new_vm_name);

            logModuleCall("VMware", "unmount vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Un-mounted. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Unmount Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Unmount Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        $mountStatus = vmwareGetMountStatus($vms, $new_vm_name, 'false');

        if ($result = "success") {

            if (trim($mountStatus) == 'true') {

                $btnStatus = 'true';

                $Status = $mountStatus;

                $btn = $_LANG['vm_unmount'];
            } elseif (trim($mountStatus) == 'false') {

                $btnStatus = 'false';

                $Status = $mountStatus;

                $btn = $_LANG['vm_mount'];
            } else {

                $btnStatus = $Status = $whmcs->get_req_var("status");

                $btn = $whmcs->get_req_var("btn");
            }
        } else {

            $btnStatus = $Status = $whmcs->get_req_var("status");

            $btn = $whmcs->get_req_var("btn");
        }

        $runtime = $vms->get_vm_info($new_vm_name)->summary->quickStats->uptimeSeconds;

        $res = array('runtime' => $runtime, 'statusmsg' => $_LANG['vm_unmount_on_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="mount_button_action(this,\'' . $btn . '\', \'' . $new_vm_name . '\',\'' . $btnStatus . '\');" class="reb_btn">' . $btn . '</a>');



        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }

    //paused
    if ($ajaxaction == 'paused') {
        $customFieldVal = vmwareGetCustomFiledVal($params);
        $new_vm_name = $customFieldVal['vm_name'];

        try {
            //            $response_obj = $vms->vm_power_off($new_vm_name);

            $response_obj = $vms->vm_suspend($new_vm_name);

            logModuleCall("VMware", "paused vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Paused (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Pause (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }

            if (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOn') {

                $btnStatus = 'poweredOn';

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $_LANG['vm_paused'];
            } elseif (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOff') {

                $btnStatus = 'poweredOff';

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $_LANG['vm_unpaused'];
            } elseif (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'suspended') {

                $btnStatus = 'poweredOff';

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $_LANG['vm_unpaused'];
            } else {

                $btnStatus = $Status = $whmcs->get_req_var("status");

                $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

                $btn = $whmcs->get_req_var("btn");
            }

            $runtime = '';
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Pause (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        $res = array('runtime' => $runtime, 'statusmsg' => $_LANG['vm_pause_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="pause_button_action(this,\'' . $btn . '\',\'' . $btnStatus . '\',\'' . $_LANG['vm_pause_alert_msg'] . '\');" class="reb_btn">' . $btn . '</a>');

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }

    //unpaused

    if ($ajaxaction == 'unpaused') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_power_on($new_vm_name);

            logModuleCall("VMware", "unpaused vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = "success";

                $description = " VM Succefully Unpaused (From Clientarea). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $result = $response_obj['state'];

                $description = "VM Unpaused (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        } catch (Exception $e) {

            $result = $e->getMessage();

            $description = "VM Unpaused (From Clientarea) Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }

        if (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOn') {

            $btnStatus = 'poweredOn';

            $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

            $btn = $_LANG['vm_paused'];
        } elseif (trim($vms->get_vm_info($new_vm_name)->summary->runtime->powerState) == 'poweredOff') {

            $btnStatus = 'poweredOff';

            $Status = $vms->get_vm_info($new_vm_name)->summary->runtime->powerState;

            $btn = $_LANG['vm_unpaused'];
        } else {

            $btnStatus = $Status = $whmcs->get_req_var("status");

            $btn = $whmcs->get_req_var("btn");
        }

        $runtime = '<div class="tab_row"><strong>System uptime</strong><span> ' . round($vms->get_vm_info($new_vm_name)->summary->quickStats->uptimeSeconds) . ' hours&nbsp;</span></div>';

        $res = array('runtime' => $runtime, 'statusmsg' => $_LANG['vm_unpause_msg'], 'status' => $Status, 'message' => $result, 'button' => '<a href="javascript:void(0);" onclick="pause_button_action(this,\'' . $btn . '\',\'' . $btnStatus . '\',\'' . $_LANG['vm_pause_alert_msg'] . '\');" class="reb_btn">' . $btn . '</a>');

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        echo json_encode($res);

        exit();
    }
    # get disks

    if ($ajaxaction == 'disks') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {
            $data = $vms->getVmDisks($new_vm_name);
            logModuleCall("VMware", "get disks", array('name' => $new_vm_name), $data);

            if (!empty($data)) {

                $html .= '<div class="row">

                                <div class="manage_tab_content">

                                    <div class="manage_heading">

                                        <h2>' . $_LANG['vm_disks'] . '</h2>

                                    </div>';

                foreach ($data as $key => $value) {

                    $capacity = round(($value->capacity) / (1024 * 1024 * 1024), 2);

                    $freeSpace = round(($value->freeSpace) / (1024 * 1024 * 1024), 2);

                    $totalPercenatge = ceil(($capacity - $freeSpace) / $capacity * 100);

                    $html .= '<div class="m_detail_cont">

                                        <p><strong>' . $_LANG['vm_disk_path'] . ': </strong>' . $value->diskPath . '</p>

                                        <p><strong>' . $_LANG['vm_capacity'] . ': </strong>' . $capacity . ' GB</p>

                                        <p><strong>' . $_LANG['vm_freespace'] . ': </strong>' . $freeSpace . ' GB</p>

                                    </div>';

                    $html .= '<div class="tab_row ftp_row"><strong><i><img src="modules/servers/vmware/images/gb-icon.png" alt=""></i>

                                            <p>' . $_LANG['vm_total'] . ': ' . $capacity . ' GB</p>

                                        </strong>

                                        <span style="min-height: 118px;"> <b>' . $_LANG['vm_disk'] . '</b>

                                            <div class="progress">

                                                <div style="width: ' . $totalPercenatge . '%;" class="progress-bar progress-success"></div>

                                            </div>

                                            <small>' . $capacity . ' GB of ' . $freeSpace . ' GB</small>

                                        </span>

                                    </div>';
                }

                $html .= '</div></div>';
            } else {

                $html = '<div class="alert alert-danger">' . $_LANG['vm_no_disk_found'] . '</div>';
            }

            echo $html;
        } catch (Exception $e) {
            echo $html = '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }

        exit();
    }

    //hard reboot

    if ($ajaxaction == 'upgrade') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $new_vm_name = $customFieldVal['vm_name'];

        try {

            $response_obj = $vms->vm_upgradevmwaretool($new_vm_name);

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $description = " VM Succefully Upgraded VMware Tool. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";

                $success = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $_LANG['vm_upgrade_tool_msg'] . '</div>';
            } else {

                $description = "VM Upgrad VMware Tool Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$response_obj['state']}";

                $status = "Failed";

                $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $response_obj['state'] . '</div>';
            }
        } catch (Exception $e) {

            $description = "VM Upgrad VMware Tool Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$e->getMessage()}";

            $status = "Failed";

            $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $e->getMessage() . '</div>';

            logModuleCall("VMware", "upgrade VM tool", array('name' => $new_vm_name), $e->getMessage());
        }

        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

        if (!empty($error))
            echo $error;
        else
            echo $success;

        exit();
    }

    //Create snap shot

    if ($ajaxaction == 'createsnapshot') {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $spLimit = (int) $customFieldVal['snapshot_limit'];
        if (empty($spLimit)) {
            $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $_LANG['vm_snapshot_limit_exceed'] . '</div>';
            $array = json_encode(array('status' => 'error', 'msg' => $error));
            print_r($array);
            exit();
        }
        $new_vm_name = $customFieldVal['vm_name'];
        $vm_info = $vms->get_vm_info($new_vm_name);

        $ret = $vm_info->getSnapshot()->rootSnapshotList;

        VmwareCountSnapShots($ret);
        $snapShotCount = $_SESSION['snapshotcounter'];
        unset($_SESSION['snapshotcounter']);



        $snapshot_name = $whmcs->get_req_var("snapshot_name");

        $snapshot_desc = $whmcs->get_req_var("snapshot_desc");

        if (empty($snapshot_name)) {

            $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_snap_shot_empty'] . "<div>";
        } elseif (empty($snapshot_desc)) {

            $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_snap_shot_desc_empty'] . "<div>";
        }

        if (!empty($snapshot_name) && !empty($snapshot_desc)) {

            try {
                if ($snapShotCount >= $spLimit) {
                    $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $_LANG['vm_snapshot_limit_exceed'] . '</div>';
                    $array = json_encode(array('status' => 'error', 'msg' => $error));
                    print_r($array);
                    exit();
                }

                $response_obj = $vms->CreateVMSnapshot($new_vm_name, $snapshot_name, $snapshot_desc);
                logModuleCall("VMware", "Create snap shot", array('name' => $new_vm_name, 'snapshot_name' => $snapshot_name, 'snapshot_desc' => $snapshot_desc), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));
                if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {
                    $success = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_create_snap_shot_msg'] . "</div>";

                    $description = "Create Snapshot. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                    $status = "Success";
                } else {

                    $description = "Create Snapshot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$response_obj['state']}";

                    $status = "Failed";

                    $error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $response_obj['state'] . '</div>';
                }
            } catch (Exception $e) {

                $description = "Create Snapshot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$e->getMessage()}";

                $status = "Failed";

                $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $e->getMessage() . "</div>";

                logModuleCall("VMware", "Create snap shot", array('name' => $new_vm_name), $e->getMessage());
            }
        }
        if (!empty($error)) {
            $array = json_encode(array('status' => 'error', 'msg' => $error));
        } else {
            $array = json_encode(array('status' => 'success', 'msg' => $success));
        }
        $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);
        print_r($array);
        exit();
    }
    // VM detail

    if ($ajaxaction == 'detail') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);
            $vm_name = $customFieldVal['vm_name'];
            $recentTaskObj = $vms->get_vm_recent_task($vm_name);
            $recentTaskArr = $WgsVmwareObj->vmware_object_to_array($recentTaskObj);

            if (isset($recentTaskArr['RetrievePropertiesResponse']['returnval'])) {

                foreach ($recentTaskArr['RetrievePropertiesResponse']['returnval'] as $recentTaskVal) {

                    if (urldecode($recentTaskVal['propSet'][0]['val']) == $vm_name) {

                        $recentTaskData = $recentTaskVal;
                    }
                }
            }

            if (count($recentTaskData['propSet'][1]['val']['ManagedObjectReference']) > 1) {

                $taskId = end($recentTaskData['propSet'][1]['val']['ManagedObjectReference']);
            } else {

                $taskId = $recentTaskData['propSet'][1]['val']['ManagedObjectReference'];
            }

            if ($taskId)
                $task = $vms->get_vm_recent_task_info($taskId)->info->name;

            $info = $vms->get_vm_info($vm_name);

            $vmslist = $WgsVmwareObj->vmware_object_to_array($info);

            $vminfo = array();

            if (isset($vmslist['RetrievePropertiesResponse']['returnval'])) {

                foreach ($vmslist['RetrievePropertiesResponse']['returnval'] as $vmsinfoValue) {

                    if (!empty($vmsinfoValue['propSet'][0]['val'])) {

                        if (urldecode($vmsinfoValue['propSet'][0]['val']) == $vm_name) {

                            $vminfo = $vmsinfoValue;
                        }
                    } else {

                        if (urldecode($vmsinfoValue[0]['val']) == $vm_name) {

                            $vminfo = $vmsinfoValue;
                        }
                    }
                }
            }



            if ($vminfo['propSet'])
                $vmData = $vminfo['propSet'][1]['val'];
            else
                $vmData = $vminfo[1]['val'];

            $vmIPInfo = $vms->get_vm_guest($vm_name);
            if ($vmIPInfo) {
                $netInfo = $vmIPInfo->getGuestInfo()->net;
                $vmIps = [];
                foreach ($netInfo as $net) {
                    foreach ($net->ipConfig->ipAddress as $ip) {
                        if (filter_var($ip->ipAddress, FILTER_VALIDATE_IP)) {
                            $vmIps[] = $ip->ipAddress;
                        }
                    }
                    break;
                }
            }


            if ($vmData['runtime']['powerState'] == 'poweredOff') {

                $status = $_LANG['vm_powered_off'];

                $powerStatus = $_LANG['vm_powered_on'];

                $pauseStatus = $_LANG['vm_unpaused'];

                $uptime = '';

                $classOff = 'poweredOff';

                $classOn = '';
            } elseif ($vmData['runtime']['powerState'] == 'poweredOn') {

                $status = $_LANG['vm_powered_on'];

                $powerStatus = $_LANG['vm_powered_off'];

                $pauseStatus = $_LANG['vm_paused'];

                $classOff = '';

                $classOn = 'poweredOn';



                $init = $vmData['quickStats']['uptimeSeconds'];

                $uptime = '<div class="tab_row" id="runtime"><strong>' . $_LANG['vm_system_uptime'] . '</strong><span>' . $WgsVmwareObj->get_time($init) . ' &nbsp;</span></div>';
            } elseif ($vmData['runtime']['powerState'] == 'suspended') {

                $status = ucfirst($vmData['runtime']['powerState']);

                $powerStatus = $_LANG['vm_powered_on'];

                $pauseStatus = $_LANG['vm_paused'];

                $classOff = 'poweredOff';

                $classOn = '';
            } else
                $status = $vmData['runtime']['powerState'];



            if ($vmData['guest']['toolsStatus'] == 'toolsNotInstalled')
                $toolStatus = $_LANG['vm_toolsNotInstalled'];
            else
                $toolStatus = $vmData['guest']['toolsStatus'];



            if ($vmData['guest']['toolsVersionStatus'] == 'guestToolsNotInstalled')
                $toolVStatus = $_LANG['vm_guesttoolsNotInstalled'];
            else
                $toolVStatus = $vmData['guest']['toolsVersionStatus'];



            $memory = $vmData['config']['memorySizeMB'];

            $usedMemory = $vmData['quickStats']['guestMemoryUsage'];

            $percentageMem = round((($usedMemory / $memory) * 100), 2);

            if ($percentageMem < 30)
                $class = 'green';

            elseif ($percentageMem < 70)
                $class = 'moderate';

            elseif ($percentageMem > 70)
                $class = 'high';

            if ($usedMemory == '')
                $usedMemory = 0;



            if ($vmData['runtime']['toolsInstallerMounted'] == 'true')
                $mountStatus = $_LANG['vm_unmount'];
            else
                $mountStatus = $_LANG['vm_mount'];

            $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->where('datacenter', $customFieldVal['datacenter'])->get();

            $osPort = '';
            if($getResources[0]->port != '')
                $osPort = $getResources[0]->port;
            $vmIPInfo = $vms->get_vm_guest($vm_name);

            $powerBtn = '<a href="javascript:void(0);" onclick="power_button_action(this, \'' . $powerStatus . '\',  \'' . $vmData['runtime']['powerState'] . '\', \'' . $_LANG['vm_power_off_alert_msg'] . '\');" class="reb_btn">' . $powerStatus . '</a>';

            $pauseBtn = '<a href="javascript:void(0);" onclick="pause_button_action(this, \'' . $pauseStatus . '\', \'' . $vmData['runtime']['powerState'] . '\', \'' . $_LANG['vm_pause_alert_msg'] . '\');" class="reb_btn">' . $pauseStatus . '</a>';

            $mountBtn = '<a href="javascript:void(0);" onclick="mount_button_action(this, \'' . $mountStatus . '\', \'' . $vmData['runtime']['toolsInstallerMounted'] . '\');" class="reb_btn">' . $mountStatus . '</a>';



            $html = '<div class="tab_row"><strong>' . $_LANG['vm_name'] . '</strong><span>' . $vm_name . ' &nbsp;</span></div>';

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_power_state'] . '</strong><span class="powerstatus">' . $status . ' &nbsp;<div class="custom_switch">

                                            <span class="' . $classOn . '" id="poweredOn" onclick="powerOn(this, \'poweredOn\', \'' . $vm_name . '\');">ON</span>

                                            <span class="' . $classOff . '" id="poweredOff" onclick="powerOff(this, \'poweredOff\', \'' . $_LANG['vm_power_off_alert_msg'] . '\', \'' . $vm_name . '\');">OFF</span>

                                        </div></span></div>';

            //$html .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_os_state'] . '</strong><span class="powerstatus">' . $status . ' &nbsp;</span></div>';

            if (!empty($vmIps))
                $html .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_ip_address'] . '</strong><span>' . implode(', ', $vmIps) . ' &nbsp;</span></div>';
            if($customFieldVal['os_type'] == 'Linux')
                $portText = "SSH Port";
            elseif($customFieldVal['os_type'] == 'Windows')
                $portText = "RDP Port";

            if($osPort)
                $html .= '<div class="tab_row"><strong>' . $portText . '</strong><span>' . $osPort . ' &nbsp;</span></div>';

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_password'] . '</strong><span>' . $params['customfields']['vm_password'] . ' &nbsp;</span></div>';
            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_dedecated_os'] . '</strong><span>' . $vmData['config']['guestFullName'] . ' &nbsp;</span></div>';

            if (isset($vmData['guest']['hostName']) && !empty($vmData['guest']['hostName']))
                $html .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_host_name'] . '</strong><span>' . $vmData['guest']['hostName'] . ' &nbsp;</span></div>';

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_tools_status'] . '</strong><span>' . $toolStatus . ' &nbsp;</span></div>';

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_tools_version'] . '</strong><span>' . $toolVStatus . ' &nbsp;</span></div>';

            if (!empty($task))
                $html .= '<div class="tab_row"><strong>' . $_LANG['vm_recent_task'] . '</strong><span>' . $task . ' &nbsp;</span></div>';

            if ($percentageMem > 100)
                $percentageMem = '100';
            else
                $percentageMem = $percentageMem;

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_memory_size'] . '</strong><span>' . $usedMemory . '/' . $memory . ' MB ' . '(' . $percentageMem . '%) <div class="percentagediv"><div class="percentage_inr ' . $class . '" style="width:' . $percentageMem . '%"></div></div> &nbsp;</span></div>';

            if ($vmData['runtime']['maxMemoryUsage'])
                $html .= '<div class="tab_row"><strong>' . $_LANG['vm_memory_usage'] . '</strong><span>' . $vmData['runtime']['maxMemoryUsage'] . ' MB &nbsp;</span></div>';

            $html .= '<div class="tab_row"><strong>' . $_LANG['vm_number_cpu'] . '</strong><span>' . $vmData['config']['numCpu'] . ' &nbsp;</span></div>';

            if ($vmData['runtime']['maxCpuUsage'])
                $html .= '<div class="tab_row"><strong>' . $_LANG['vm_cpu_usage'] . '</strong><span>' . $vmData['runtime']['maxCpuUsage'] . ' MHz &nbsp;</span></div>';

            $html .= $uptime;
        } catch (Exception $ex) {

            $html = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' . $ex->getMessage() . '</div>';
        }

        print_r(json_encode(array('html' => $html, 'powerbtn' => $powerBtn, 'pausebtn' => $pauseBtn, 'mountbtn' => $mountBtn)));

        exit();
    }



    if ($ajaxaction == 'btns') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];



            if ($vms->get_vm_info($vm_name)->summary->runtime->powerState == 'poweredOff') {

                $powerStatus = $_LANG['vm_powered_on'];

                $pauseStatus = $_LANG['vm_unpaused'];
            } elseif ($vms->get_vm_info($vm_name)->summary->runtime->powerState == 'poweredOn') {

                $powerStatus = $_LANG['vm_powered_off'];

                $pauseStatus = $_LANG['vm_paused'];
            } elseif ($vms->get_vm_info($vm_name)->summary->runtime->powerState == 'suspended') {

                $powerStatus = $_LANG['vm_powered_on'];

                $pauseStatus = $_LANG['vm_unpaused'];
            }



            if ($vms->get_vm_info($vm_name)->summary->runtime->toolsInstallerMounted == 'true')
                $mountStatus = $_LANG['vm_unmount'];
            else
                $mountStatus = $_LANG['vm_mount'];



            $powerBtn = '<a href="javascript:void(0);" onclick="power_button_action(this, \'' . $powerStatus . '\', \'' . $vm_name . '\', \'' . $vms->get_vm_info($vm_name)->summary->runtime->powerState . '\', \'' . $_LANG['vm_power_off_alert_msg'] . '\');" class="reb_btn">' . $powerStatus . '</a>';

            $pauseBtn = '<a href="javascript:void(0);" onclick="pause_button_action(this, \'' . $pauseStatus . '\', \'' . $vms->get_vm_info($vm_name)->summary->runtime->powerState . '\', \'' . $_LANG['vm_pause_alert_msg'] . '\');" class="reb_btn">' . $pauseStatus . '</a>';

            $mountBtn = '<a href="javascript:void(0);" onclick="mount_button_action(this, \'' . $mountStatus . '\', \'' . $vm_name . '\', \'' . $vms->get_vm_info($vm_name)->summary->runtime->toolsInstallerMounted . '\');" class="reb_btn">' . $mountStatus . '</a>';
        } catch (Exception $ex) {
        }



        print_r(json_encode(array('powerbtn' => $powerBtn, 'pausebtn' => $pauseBtn, 'mountbtn' => $mountBtn)));

        exit();
    }



    if ($ajaxaction == 'snapshot_list') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];



            $html = $vms->snapshotList($vm_name, $params['userid'], $params['serviceid'], $_LANG['vm_save'], $_LANG['vm_rename'], $_LANG['vm_snapshot_list_not_found'], $_LANG['vm_loding']);
        } catch (Exception $ex) {

            $html = '<tr><td colspan="100%>' . $ex->getMessage() . '</td></tr>';
        }

        echo $html;

        exit();
    }



    if ($ajaxaction == 'snapshot_update') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];

            $snapshot_name = $whmcs->get_req_var("name");

            $snapshot_desc = $whmcs->get_req_var("desc");

            $org_name = $whmcs->get_req_var("org_name");

            $spid = $whmcs->get_req_var("spid");

            if (empty($snapshot_name)) {

                $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_snap_shot_empty'] . "<div>";
            } elseif (empty($snapshot_desc)) {

                $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_snap_shot_desc_empty'] . "<div>";
            }



            if (!empty($snapshot_name) && !empty($snapshot_desc)) {

                try {

                    $response_obj = $vms->RenameVMSnapshot($vm_name, $snapshot_name, $snapshot_desc, $spid, $org_name);

                    logModuleCall("VMware", "rename snap shot", array('VMname' => $vm_name, 'name' => $org_name, 'snapshot_name' => $snapshot_name, 'snapshot_name' => $snapshot_desc, 'snapshot id' => $spid), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

                    if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                        $description = "Snapshot Updated. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                        $status = "Success";

                        $success = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_rename_snap_shot_msg'] . "</div>";
                    } else {

                        $description = "Snapshot Update Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$response_obj['state']}";

                        $status = "Failed";

                        $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $response_obj['state'] . "</div>";
                    }
                } catch (Exception $e) {

                    $description = "Snapshot Update Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$e->getMessage()}";

                    $status = "Failed";

                    $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $e->getMessage() . "</div>";

                    logModuleCall("VMware", "rename snap shot", array('name' => $vm_name, 'snapshot_name' => $snapshot_name, 'snapshot_name' => $snapshot_desc, 'snapshot id' => $spid), $e->getMessage());
                }
            }
        } catch (Exception $ex) {

            $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $ex->getMessage() . "</div>";
        }



        $vms->storeVmwareLogs($sid, $vm_name, $description, $status);

        if (!empty($error))
            print_r(json_encode(array('status' => 'error', 'msg' => $error)));
        else
            print_r(json_encode(array('status' => 'success', 'msg' => $success)));

        exit();
    }



    if ($ajaxaction == 'revert_latest_sp') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];

            $revert = $vms->revertSnapshot($vm_name);

            logModuleCall("VMware", "revert to latest snapshot", array('name' => $vm_name), $WgsVmwareObj->vmware_object_to_array($revert['obj']));

            if ($revert['state'] == 'success' || $response_obj['state'] == '') {

                $description = "Reverted Latest Snapshot. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";

                $success = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_revert_latest_snap_shot_msg'] . "</div>";
            } else {

                $description = "Revert Latest Snapshot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$revert['state']}";

                $status = "Failed";

                $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $revert['state'] . "</div>";
            }
        } catch (Exception $ex) {

            $description = "Revert Latest Snapshot Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$ex->getMessage()}";

            $status = "Failed";

            logModuleCall("VMware", "revert to latest snapshot", array('name' => $vm_name), $ex->getMessage());

            $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $ex->getMessage() . "</div>";
        }

        $vms->storeVmwareLogs($sid, $vm_name, $description, $status);

        if (!empty($error))
            echo $error;
        else
            echo $success;

        exit();
    }



    if ($ajaxaction == 'remove_all_sp') {

        try {

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];

            $result = $vms->removeAllSnapshot($vm_name);

            logModuleCall("VMware", "remove all snapshot", array('name' => $vm_name), $WgsVmwareObj->vmware_object_to_array($result['obj']));

            if ($result['state'] == 'success' || $response_obj['state'] == '') {

                $success = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_remove_all_snap_shot_msg'] . "</div>";



                $description = "Removed All Sanpshots. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";
            } else {

                $description = "Remove All Sanpshots Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result['state']}";

                $status = "Failed";

                $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $result['state'] . "</div>";
            }
        } catch (Exception $ex) {

            $description = "Remove All Sanpshots Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$ex->getMessage()}";

            $status = "Failed";

            logModuleCall("VMware", "remove all snapshot", array('name' => $vm_name), $ex->getMessage());

            $error = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $ex->getMessage() . "</div>";
        }

        $vms->storeVmwareLogs($sid, $vm_name, $description, $status);

        if (!empty($error))
            echo $error;
        else
            echo $success;

        exit();
    }



    // Remove selected snapshot



    if ($ajaxaction == 'delete_multi') {

        $slected_sp = $whmcs->get_req_var("slected_sp");

        $child = $whmcs->get_req_var("child");

        $org_name = $whmcs->get_req_var("org_name");

        $snapshot = $whmcs->get_req_var("snapshot");

        if (isset($slected_sp)) {

            $response = '';

            foreach ($slected_sp as $key => $data) {

                if (isset($child) && $child == 'yes') {

                    $child = true;
                } else {

                    $child = false;
                }

                try {

                    $customFieldVal = vmwareGetCustomFiledVal($params);
                    $vm_name = $customFieldVal['vm_name'];

                    $results = $vms->removeSelectedSnapshot($org_name[$key], $child);

                    logModuleCall("VMware", "remove selected snapshot", array('name' => $vm_name), $WgsVmwareObj->vmware_object_to_array($results['obj']));

                    if ($results['state'] == 'success' || $response_obj['state'] == '') {

                        $description = "Removed Multiple Snapshots. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                        $status = "Success";

                        $response .= "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>(" . $snapshot[$key] . ") " . $_LANG['vm_remove_selected_snap_shot_msg'] . "</div>";
                    } else {

                        $description = "Remove Multiple Snapshots Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$results['state']}";

                        $status = "Failed";

                        $response = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $results['state'] . "</div>";
                    }
                } catch (Exception $ex) {

                    $description = "Remove Multiple Snapshots Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$ex->getMessage()}";

                    $status = "Failed";

                    logModuleCall("VMware", "remove selected snapshot", array('name' => $snapshot), $ex->getMessage());

                    $response .= "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>(" . $snapshot[$key] . ") " . $ex->getMessage() . "</div>";
                }
            }

            $vms->storeVmwareLogs($sid, $vm_name, $description, $status);

            echo $response;
        }

        exit();
    }



    if ($ajaxaction == 'migrate') {



        if (Capsule::table('mod_vmware_migration_list')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->where('status', '0')->count() > 0) {

            echo "<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_migrate_alrdy_sent_msg'] . "</div>";

            exit();
        }

        $customFieldVal = vmwareGetCustomFiledVal($params);



        $vm_name = $customFieldVal['vm_name'];



        $existhost = $whmcs->get_req_var("existhost");

        $r_pool = $whmcs->get_req_var("r_pool");

        $vm_host_name = $whmcs->get_req_var("vm_host_name");

        $vm_dc = $whmcs->get_req_var("vm_dc");

        $values = [
            'sid' => $params['serviceid'],
            'uid' => $params['userid'],
            'server_id' => $serverData[0]->id,
            'status' => '0',
            'user' => $params['clientsdetails']['fullname'],
            'from_host' => $existhost,
            'r_pool' => $r_pool,
            'vmname' => $vm_name,
            'to_host' => $vm_host_name,
            'datacenter' => $vm_dc,
            'reason' => '',
        ];

        try {

            $id = Capsule::table('mod_vmware_migration_list')->insertGetId($values);
        } catch (Exception $ex) {

            logActivity("could't insert into table mod_vmware_migration_list error: {$ex->getMessage()}");

            echo "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $ex->getMessage() . "</div>";

            exit();
        }



        $user_migration_content = '<p>' . $_LANG['vm_admin_migrate_msg'] . $existhost . ' ' . $_LANG['vm_admin_migrate_to'] . ' ' . $vm_host_name . '</p>';

        $adminArr = Capsule::table('tbladmins')->select('id')->get();

        $admin = $adminArr[0]->id;

        $command = "sendadminemail";

        $adminuser = $admin;

        $values["messagename"] = "VMware Migration Request Notification Email";

        $values["customsubject"] = "VMware Migration Request Notification";

        $values["mergefields"] = array(
            "client_name" => $params['clientsdetails']['fullname'],
            "client_id" => $params['userid']
        );

        $values["type"] = "system";

        $values["customvars"] = array("user_migration_content" => $user_migration_content, "addon_migrate_link" => $CONFIG['SystemURL'] . '/admin/addonmodules.php?module=vmware&action=migrate_vms&id=' . $id);



        $results = localAPI($command, $values, $adminuser);

        if ($results['result'] == 'success') {

            $response = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_migrate_success_msg'] . "</div>";
        } else {

            $response = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>(" . $_LANG['vm_migrate_error_msg'] . ") " . $ex->getMessage() . "</div>";
        }

        echo $response;

        exit();
    }



    if ($ajaxaction == 'getdchost') {



        $dc = $whmcs->get_req_var("dc");

        $get_datacenter = $vms->list_datacenters_host($dc);

        $getDatacenterHostArr = $WgsVmwareObj->vmware_object_to_array($get_datacenter);

        if ($get_datacenter) {

            $hostname = '';

            foreach ($get_datacenter as $getDatacenterHostArr) {



                if (!empty($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'])) {

                    foreach ($getDatacenterHostArr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $datacenterList) {

                        if ($datacenterList['name'] == 'host') {

                            if (count($datacenterList['val']['ManagedObjectReference']) > 1) {

                                $host = $datacenterList['val']['ManagedObjectReference'];
                            } else {

                                $host = array($datacenterList['val']['ManagedObjectReference']);
                            }

                            foreach ($host as $hostValue) {

                                $host_resource_arr = $WgsVmwareObj->vmware_object_to_array($vms->get_host_resources($hostValue));

                                foreach ($host_resource_arr['RetrievePropertiesResponse']['returnval']['propSet'] as $key => $hostResValue) {

                                    if ($hostResValue['name'] == 'name') {

                                        $hostname .= '<option value="' . $hostResValue['val'] . '" obj="' . $hostValue . '">' . $hostResValue['val'] . '</option>';
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $hostname = '<option value="">Not Found</option>';
                }
            }
        } else {

            $hostname = '<option value="">Not Found</option>';
        }

        if ($hostname)
            echo $hostname;
        else
            echo '<option value="">Not Found</option>';



        exit();
    }



    if ($ajaxaction == 'getoslist_and_id') {



        $option = '';

        if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/addons/vmware/guestOs/guestOsIdentifier.txt')) {

            $GuestOsVariableArr = file(dirname(dirname(dirname(dirname(__FILE__)))) . '/addons/vmware/guestOs/guestOsIdentifier.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        } else {

            $option = 'guestOsIdentifier.txt file missing';
        }



        foreach ($GuestOsVariableArr as $key => $OsVersions) {

            $OsVersionsArr = explode('-', $OsVersions);



            $versionid = $whmcs->get_req_var("versionid");

            $osVersion = $whmcs->get_req_var("osVersion");

            $osVersionValue = $whmcs->get_req_var("osVersionValue");

            if (!isset($versionid)) {

                if ($OsVersionsArr[0] == $osVersion) {

                    $option .= '<option value="' . $OsVersionsArr[1] . '">' . $OsVersionsArr[1] . '</option>';
                }
            } elseif (isset($versionid)) {

                if ($OsVersionsArr[1] == $osVersionValue) {

                    $option = $OsVersionsArr[2];
                }
            }
        }

        echo $option;

        exit();
    }

    if ($ajaxaction == 'getoslist') {
        $os_option = '';
        $api_server = $params['configoption3'];

        $os_list = $whmcs->get_req_var("os_list");

        $dc = $customFieldVal['datacenter'];

        $customFiledID = Capsule::table('tblcustomfields')->select('id')->where('type', 'product')->where('relid', $params['pid'])->where('fieldname', 'like', '%guest_os_version%')->get();

        $productQuery = Capsule::table('tblcustomfieldsvalues')->select('value')->where('fieldid', $customFiledID[0]->id)->where('relid', $params['serviceid'])->get();

        $getServerId = Capsule::table('mod_vmware_server')->where('server_name', $api_server)->first();
        if (count($getServerId) == 0)
            $getServerId = Capsule::table('mod_vmware_server')->where('id', $api_server)->first();

        $getServerId = $getServerId->id;

        $cloneOsListQryArr = array();

        $iso_osListQryArr = array();

        if ($defaultHost != 'none') {
            $hostsystem_name = $vms->get_host_parent($defaultHost)->name;
            $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->get();
        } else
            $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->get();

        $i = 0;

        foreach ($cloneOsListQry as $key => $os_lists) {

            $cloneOsListQryArr[$key] = $os_lists->customname;
        }
        if ($defaultHost != 'none') {
            $hostsystem_name = $vms->get_host_parent($defaultHost)->name;
            $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->get();
        } else
            $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('os_family', $os_list)->where('status', '')->where('server_id', $getServerId)->get();

        foreach ($iso_osListQry as $key => $os_list) {

            $iso_osListQryArr[$key] = $os_list->os_version;
        }

        $osListQry = array_unique(array_merge($cloneOsListQryArr, $iso_osListQryArr));

        foreach ($osListQry as $os_list) {
            if ($productQuery[0]->value == $os_list)
                $sel = 'selected="selected"';
            else
                $sel = '';

            $os_option .= '<option ' . $sel . ' value="' . $os_list . '">' . $os_list . '</option>';

            $os_version .= $os_list . ',';
        }

        $os_version = rtrim($os_version, ',');

        try {

            $updatedUserCount = Capsule::table('tblcustomfields')
                ->where('id', $customFiledID[0]->id)
                ->update(
                    [
                        'fieldoptions' => $os_version,
                    ]
                );
        } catch (\Exception $e) {
        }

        die($os_option);

        exit();
    }

    if ($ajaxaction == 'reinstallvm') {
        global $whmcs;
        try {

            if (!Capsule::Schema()->hasTable('mod_vmware_reinstall_vm')) {
                Capsule::schema()->create('mod_vmware_reinstall_vm', function ($table) {
                    $table->increments('id');
                    $table->string('userid')->nullable();
                    $table->string('serviceid')->nullable();
                    $table->longText('data')->nullable();
                    $table->string('os_family')->nullable();
                    $table->string('os_version')->nullable();
                    $table->integer('status')->nullable();
                });
            }

            $existData = Capsule::table("mod_vmware_reinstall_vm")->where('serviceid', $params['serviceid'])->where('status', '0')->first();
            if (count($existData) == 0) {
                $data = ['userid' => $params['userid'], 'serviceid' => $params['serviceid'], 'data' => json_encode($params), 'os_family' => $whmcs->get_req_var('os_name'), 'os_version' => $whmcs->get_req_var('os_version'), 'status' => '0'];
                Capsule::table("mod_vmware_reinstall_vm")->insert($data);

                $description = "Reinstall request submit successfully. <a href=\"clientsservices.php?id={$params['serviceid']}\" target=\"_blank\">Service ID: {$params['serviceid']}</a>";
                $logStatus = "Success";
                $status = 'success';
                $response = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reinstall_msg'] . "&nbsp;<button onclick='location.reload()'>" . $_LANG['vm_reinstall_btn_reload'] . "</button></div>";
                $vms->storeVmwareLogs($params['serviceid'], $vm_name, $description, $status);
            } else {
                $response = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>Error: " . $_LANG['vm_reinstall_data_exist'] . "!</div>";
                $vms->storeVmwareLogs($params['serviceid'], $vm_name, "Server Reinstall Request already pending", "Failed");
            }
        } catch (Exception $ex) {
            $vms->storeVmwareLogs($params['serviceid'], $vm_name, "Reinstall server Error: {$ex->getMessage()}", "Failed");
            $error = "Error: {$ex->getMessage()}";
            $response = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>Error: " . $error . "!</div>";
            $status = 'error';
        }
        print_r(json_encode(array('status' => $status, 'msg' => $response)));
        exit();
    }

    if("changevmpw" ==  $ajaxaction){
        global $whmcs;

        $newPw = $whmcs->get_req_var('vmpw');
        $cfpw = $whmcs->get_req_var('cfvmpw');
        if($newPw != $cfpw){
            print_r(json_encode(array('status' => "error", 'msg' => $_LANG['vm_reset_password_donotmatch'])));
        }else{
            $customFieldVal = vmwareGetCustomFiledVal($params);
            $vm_name = $customFieldVal['vm_name'];
            $osType = $customFieldVal['os_type'];
            $guetOsVersion = $customFieldVal['os_version'];
            $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->first();
            if(count($getCloneVmName ) == 0)
                $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->first();
            if ($defaultHost != 'none')
                $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->first();
            
            $cloneVmName = $getCloneVmName->vmtemplate;
            if($whmcs->get_req_var('ostype') == "Linux"){
                $os = "";
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

                if ($os == 'centos' || $os == 'rhel' || $os == 'alma' || $os == 'cloud') {
                    $cmd = '/bin/echo \'' . $newPw . '\' | sudo -S passwd --stdin root'; # CentOS
                } elseif ($os == 'ubuntu') {
                    $cmd = 'echo root:\'' . $newPw . '\' | sudo chpasswd'; # Ubuntu
                }elseif ($os == 'debian') {
                    $cmd = '/bin/echo \'root:' . $newPw . '\' | chpasswd root'; # debian
                }

                $vmIPInfo = $vms->get_vm_guest($vm_name);
                $vmPowerStatus = $vms->get_vm_info($vm_name)->summary->runtime->powerState;
                if("poweredOn" != $vmPowerStatus){
                    $response = "<div style='margin-top: 20px;' class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_poweron_msg'] . "</div>";
                    $status = "failed";
                    $vms->storeVmwareLogs($sid, $vm_name, $_LANG['vm_reset_pw_poweron_msg'], "Failed");
                    print_r(json_encode(array('status' => $status, 'msg' => $response)));
                    exit();
                }
                if ($vmIPInfo) {
                    $netInfo = $vmIPInfo->getGuestInfo()->net;
                    $IP = "";
                    foreach ($netInfo as $net) {
                        foreach ($net->ipConfig->ipAddress as $ip) {
                            if (filter_var($ip->ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                $IP = $ip->ipAddress;
                            }
                        }
                        break;
                    }
                }
                if("" == $IP){
                    $response = "<div style='margin-top: 20px;' class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_poweron_ipmissing'] . "</div>";
                    $status = "failed";
                    $vms->storeVmwareLogs($sid, $vm_name, $_LANG['vm_reset_pw_poweron_ipmissing'], "Failed");
                    print_r(json_encode(array('status' => $status, 'msg' => $response)));
                    exit();
                }
                $username = "sysuser";
                $oldPw = "ubuntu@@123$$%%";
                $oldPw = "Redhat@123";
                $port = 22;
                if($getCloneVmName->port != "")
                    $port = $getCloneVmName->port;
                $ssh = new SSH2($IP, $port);
                if($ssh->login($username, $oldPw)){
                    $status = $ssh->exec(trim($cmd));
                    if (strpos($status, "error")) {
                        $response = "<div style='margin-top: 20px;' class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_msg_failed'] . "Error: {$status}</div>";
                        $msg = "Reset Password Failed ip: {$IP}, VMname : {$vm_name} Error: {$status}";
                        $logStatus = "Failed";
                    } elseif (strpos($status, "expired")) {
                        $response = "<div style='margin-top: 20px;' class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_msg_failed'] . "Error: {$status}</div>";
                        $msg = "Reset Password Failed ip: {$IP}, VMname : {$vm_name} Error: {$status}";
                        $logStatus = "Failed";
                    } else if ($status) {
                        $response = "<div style='margin-top: 20px;' class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_success_msg'] . "</div>";
                        $msg = "Reset Password Suceefully ip: {$IP}, VMname : {$vm_name} Message: {$status}";
                        $logStatus = "Success";
                        $command = "updateclientproduct";
                        $adminuser = "";
                        $values["serviceid"] = $params['serviceid'];
                        $values["servicepassword"] = $newPw;
                        $results = localAPI($command, $values, $adminuser);
                        /* Password Reset Email */
                        $values["messagename"] = 'VMware Server Password Reset Email';
                        $values["customvars"] = base64_encode(serialize(array("vmname" => $vm_name)));
                        $values["id"] = $params['serviceid'];
                        $results = localAPI("sendemail", $values);
                    } else {                            
                        if($vm->os == 'debian'){
                            if($status == ''){
                                $msg = "Reset Password Suceefully ip: {$IP}, VMname : {$vm_name} Message: {$status}";
                                $logStatus = "Success";
                                $command = "updateclientproduct";
                                $adminuser = "";
                                $values["serviceid"] = $params['serviceid'];
                                $values["servicepassword"] = $newPw;
                                $results = localAPI($command, $values, $adminuser);
                                /* Password Reset Email */
                                $values["messagename"] = 'VMware Server Password Reset Email';
                                $values["customvars"] = base64_encode(serialize(array("vmname" => $vm_name)));
                                $values["id"] = $params['serviceid'];
                                $results = localAPI("sendemail", $values);
                            }
                        }else{
                            $msg = "Reset Password Failed ip: {$IP}, VMname : {$vm_name} Error: Unkown Error";
                            $logStatus = "Failed";
                        }
                    }
                }else{
                    $msg = 'Oops, Not able to make connection with server to reset password.';
                    $logStatus = "Failed";
                }
                
                $vms->storeVmwareLogs($sid, $vm_name, $msg, $logStatus);
                $ssh->exec('exit');
                print_r(json_encode(array('status' => $logStatus, 'msg' => $response)));
            }else{
                if ($vms->get_vm_info($vm_name)->summary->runtime->powerState != 'poweredOff') {
                    $vms->vm_power_off($vm_name);
                }
                $clone = true;
    
                $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->first();

                if (count($getResources) == 0) {
                    $clone = false;
                    $getResources = Capsule::table('mod_vmware_os_list')->where('server_id', $serverData[0]->id)->where('os_version', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->first();
                }
                $netWorkDetail = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('forvm', $vm_name)->get();
                
                $netWorkDetail = (array) $netWorkDetail[0];
                
                $networkIp = $netWorkDetail['ip'];
                $netmask = $netWorkDetail['netmask'];
                $gateway = $netWorkDetail['gateway'];
                $dns = $netWorkDetail['dns'];
                $rDNS = $netWorkDetail['reversedns'];
        
                $macaddress = $netWorkDetail['macaddress'];
                $dhcp = $params['configoption18'];
                $companyname = $params['clientsdetails']['companyname'];
                $autoConfiguration = $getResources->autoconfig;
                $dataArr = [
                    'vmname' => $vm_name,
                    'pw' => $newPw,
                    'serviceid' => $params['serviceid'],
                    'companyname' => $companyname,
                    'dhcp' => $dhcp,                    
                    'networkIp' => $networkIp,
                    'netmask' => $netmask,
                    'gateway' => $gateway,
                    'dns' => $dns,
                    'rdns' => $rDNS,
                    'macaddress' => $macaddress,
                    'autoConfiguration' => $autoConfiguration
                    ];
                $ret = $vms->resetVmPw($dataArr);
                if($ret == "success"){
                    $response = "<div style='margin-top: 20px;' class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_success_msg'] . "</div>";
                    $msg = "Reset Password Suceefully ip: {$IP}, VMname : {$vm_name} Message: {$status}";
                    $logStatus = "Success";
                    $command = "updateclientproduct";
                    $adminuser = "";
                    $values["serviceid"] = $params['serviceid'];
                    $values["servicepassword"] = $newPw;
                    $results = localAPI($command, $values, $adminuser);
                    /* Password Reset Email */
                    $values["messagename"] = 'VMware Server Password Reset Email';
                    $values["customvars"] = base64_encode(serialize(array("vmname" => $vm_name)));
                    $values["id"] = $params['serviceid'];
                    $results = localAPI("sendemail", $values);
                    $vms->vm_power_on($vm_name);
                }else{
                    $response = "<div style='margin-top: 20px;' class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>" . $_LANG['vm_reset_pw_msg_failed'] . "Error: {$ret}</div>";
                        $msg = "Reset Password Failed ip: {$IP}, VMname : {$vm_name} Error: {$ret}";
                        $logStatus = "Failed";
                }
                
                $vms->storeVmwareLogs($sid, $vm_name, $msg, $logStatus);
                print_r(json_encode(array('status' => $logStatus, 'msg' => $response)));
            }
        }
        exit();
    }
}

function vmwareGetMountStatus($vms, $new_vm_name, $value)
{

    if ($vms->get_vm_info($new_vm_name)->summary->runtime->toolsInstallerMounted == $value) {

        return $vminfo['propSet'][1]['val']['runtime']['toolsInstallerMounted'];
    } else {

        return vmwareGetMountStatus($vms, $new_vm_name, $value);
    }
}

function VmwareCountSnapShots($ret){
    $count = $_SESSION['snapshotcounter'];
    foreach($ret as $value){
        if (is_array($value->childSnapshotList)) {
            $_SESSION['snapshotcounter'] = $count + 1;
            $arr[] = VmwareCountSnapShots($value->childSnapshotList);
        }else{
            $_SESSION['snapshotcounter'] = $count + 1;
            $arr[] = VmwareCountSnapShots($value->childSnapshotList);
        }
    }
}
