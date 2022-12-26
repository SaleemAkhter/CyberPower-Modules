<?php

/* * ****************************************************************
 *  WGS Linodes WHMCS Provisioning Module By whmcsglobalservices.com
 *  Copyright whmcsglobalservices, All Rights Reserved
 * 
 *  Release: 24 July, 2020
 *  WHMCS Version: v6,v7
 *  Version: 2.0.2
 *  Last Update: 24 July, 2020
 *  
 *  By WHMCSGLOBALSERVICES https://whmcsglobalservices.com
 *  Contact                   info@whmcsglobalservices.com
 * 
 *  This module is made under license issued by whmcsglobalservices.com
 *  and used under all terms and conditions of license.    Ownership of 
 *  module can not be changed.     Title and copy of    module  is  not
 *  available to any other person.
 *  
 *  @owner <whmcsglobalservices.com>
 *  @author <Meenu Bedi>
 * ********************************************************** */

if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

if (file_exists(__DIR__ . DS . 'classes' . DS . 'class_domain.php'))
    require_once __DIR__ . DS . 'classes' . DS . 'class_domain.php';

if (file_exists(__DIR__ . DS . 'classes' . DS . 'class.php'))
    require_once __DIR__ . DS . 'classes' . DS . 'class.php';

function linodes_MetaData() {
    return array(
        'DisplayName' => 'WGS Linode',
    );
}

function linodes_ConfigOptions($params) {	

    if (isset($_GET['id']) && !empty($_GET['id']))
        $pid = $_GET['id'];
    else
        $pid = $_POST['id'];

    $Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus == 'Active') {
 // get datacenter list ........................................................................................
        $allDatacenter = $Linodes->getDataCenters();
		$allDatacenterLabel = $Linodes->getDataCentersLabel();
		$dataCenterFinal = '';
		foreach($allDatacenterLabel as $key => $data){
			$dataCenterFinal .= $key .'|'. $data . ',';
		}
		$finalDataCenter = rtrim($dataCenterFinal, ",");
	// get templates list .............................................................................................
        $allTemplate = $Linodes->getTemplates();
        $templt = '';
        foreach ($allTemplate['data'] as $templateAl) {
			if($templateAl['type'] == 'manual'){
				$templt .= $templateAl['id'] . '|' . $templateAl['label'] . ',';
			}
        }
        $FinalTempl = rtrim($templt, ",");		
# Fields array
        $Linodes->createCustomFields($pid, array('name' => 'linode_id|Linode Id', 'fieldtype' => 'text', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'root_password|Root Password', 'fieldtype' => 'password', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'config_id|Config Id', 'fieldtype' => 'text', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'swap_disk_id|Swap Disk Id', 'fieldtype' => 'text', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'main_disk_id|Main Disk Id', 'fieldtype' => 'text', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'datacenter|Region', 'fieldtype' => 'dropdown', 'description' => '', 'fieldoptions' => $finalDataCenter, 'regexpr' => '', 'adminonly' => '', 'required' => 'on', 'showorder' => 'on', 'showinvoice' => '','sortorder'=>'0'));
        $Linodes->createCustomFields($pid, array('name' => 'distribution|Images', 'fieldtype' => 'dropdown', 'description' => '', 'fieldoptions' => $FinalTempl, 'regexpr' => '', 'adminonly' => '', 'required' => 'on', 'showorder' => 'on', 'showinvoice' => '','sortorder'=>'2'));
        $Linodes->createCustomFields($pid, array('name' => 'reverse_dns|Reverse DNS', 'fieldtype' => 'text', 'description' => 'If you want to show your reverse dns, Then you can set here.', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
//            $Linodes->createCustomFields($pid, array('name' => 'domain_id|Domain Id', 'fieldtype' => 'text', 'description' => 'If you want to show your reverse dns, Then you can set here.', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => ''));
        $Linodes->createCustomFields($pid, array('name' => 'resoucres_id|Resources Id', 'fieldtype' => 'text', 'description' => '', 'fieldoptions' => '', 'regexpr' => '', 'adminonly' => 'on', 'required' => '', 'showorder' => '', 'showinvoice' => '','sortorder'=>'0'));
# create Addon for backup and private IP
		$optionarray = array(
					"backup"=>array("name"=>"Backups","description"=>"Three backup slots are executed and rotated automatically: a daily backup, a 2-7 day old backup, and an 8-14 day old backup. Plans are priced according to the Linode plan selected above.","monthlyprice"=>"3.00"),
					"privateIP"=>array("name"=>"Private IP","description"=>"","monthlyprice" => "0.00")
						);
		$Linodes->createAddon($pid,$optionarray);
#create addon for backup and private ip 
        $configarray = array(
            "License" => array(
                "Type" => "na",
                "Description" => '<span>Your license key is ' . $licenseStatus . '</span>'
			)
        );
    } else {
        $configarray = array(
            "License invalid" => array(
                "Type" => "na",
                "Description" => '<span style="color:#ff0000;">Your license key is ' . $licenseStatus . ', if you have already entered valid license key, please reload the addon module page, please <a target="_blank" href="addonmodules.php?module=linode_manager">click here</a>.</span>'
            )
        );
    }
    return $configarray;
}

function linodes_CreateAccount($params) {
	$Linodes = new Linodes($params);
	$selectaddon = Capsule::table("tblhostingaddons")->where("hostingid",$params['serviceid'])->get();
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active') {
        return 'Error: Module license is ' . $licenseStatus . '.';
    }
	$getConfiguration = $Linodes->getConfiguration();
	$getproductsetting = Capsule::table("linode_product_setting")->where("product_id",$params['pid'])->first();
	if($getproductsetting->status == "0"){
		 return 'Error: This plan is not available now if you want this plan.Please contact support';
	}
	$privateIP = '';
	$backup = false;
	$backupname = 'Backups'.$params['pid'];
	$backupid = $Linodes->getAddonDetail($backupname);
	$ipid = $Linodes->getAddonDetail('Private IP');
	if(!empty($selectaddon)){
		foreach($selectaddon as $orderaddon){
			if($backupid == $orderaddon->addonid){
				$backup = true;
			}elseif($ipid == $orderaddon->addonid){
				$privateIP = 'yes';
			}
		}
	}
    $csFieldLinodeId = $Linodes->checkCsfield('linode_id', $params['pid']);
    $csFieldRootPwId = $Linodes->checkCsfield('root_password', $params['pid']);
    $csFieldSwapDiskId = $Linodes->checkCsfield('swap_disk_id', $params['pid']);
    $csFieldDiskId = $Linodes->checkCsfield('main_disk_id', $params['pid']);
    $csFieldConfigId = $Linodes->checkCsfield('config_id', $params['pid']);
    $csFieldDomainId = $Linodes->checkCsfield('domain_id', $params['pid']);
    $csFieldResourcesId = $Linodes->checkCsfield('resoucres_id', $params['pid']);
    $csFieldRdnsId = $Linodes->checkCsfield('reverse_dns', $params['pid']);

    if (empty($csFieldLinodeId))
        return 'Product custom filed "Linode Id" is missing. Please create custom filed `linode_id|Linode Id`';
    elseif (empty($csFieldSwapDiskId))
        return 'Product custom filed "Swap Disk Id" is missing. Please create custom filed `swap_disk_id|Swap Disk Id`';
    elseif (empty($csFieldDiskId))
        return 'Product custom filed "Main Disk Id" is missing. Please create custom filed `main_disk_id|Main Disk Id`';
    elseif (empty($csFieldConfigId))
        return 'Product custom filed "Config Id" is missing. Please create custom filed `config_id|Config Id`';
	//$label = $getConfiguration->prefix.'_'.$params['clientsdetails']['firstname'].'_'.$params['serviceid'];
	$label = $getConfiguration->prefix.'_'.$params['serviceid'];
    $rootPassword = $Linodes->linodesGenerateStrongPassword(12);
	
    if (empty($params['customfields']['root_password']))
        $params['customfields']['root_password'] = $rootPassword;

    if (empty($params['customfields']['root_password'])) {
        return "Root password can't be empty";
    }

    //$dCentersArr = explode('#', $params['customfields']['datacenter']);
    $datacenterId = $params['customfields']['datacenter']; 
    $distributionId = $params['customfields']['distribution'];

    $paymentTerm = $getproductsetting->subscription;
    $planId = $getproductsetting->linode_plan;
    $linodePlanForDisk = $Linodes->getLinodesPlans();
    foreach ($linodePlanForDisk['data'] as $planssForDisk) {
        if ($planssForDisk['id'] == $planId) {
            $DiskSizeGet = $planssForDisk['disk'];
        }
    }
    if (empty($params['customfields']['linode_id'])) {
		$diskLimit = (int)$getproductsetting->swap;
		$checkstackexist = Capsule::table("linode_product_setting")->where("product_id",$params['pid'])->first();
		if($checkstackexist->stack_script == 1){
			$stackID = (int)$params['customfields']['stackscript'];
			$createLinode = $Linodes->createStackLinode($label,$datacenterId, $planId, $paymentTerm,$backup,$distributionId,$diskLimit,$params['customfields']['root_password'],$stackID);
		}else{
			$createLinode = $Linodes->createLinode($label,$datacenterId, $planId, $paymentTerm,$backup,$distributionId,$diskLimit,$params['customfields']['root_password']);
		}
		if (isset($createLinode['errors'])) {
            return 'ERRORMESSAGE: ' . $createLinode['errors'][0]['reason'];
        }
        $updatelinodeId = $Linodes->updateWhmcsProduct($params, $createLinode['id'], $csFieldLinodeId);
        $linodeId = $createLinode['id'];
		$getDiskList = $Linodes->getDiskList($linodeId);
		foreach($getDiskList['data'] as $data){
			if($data['filesystem'] == "ext4"){
				$updateDiskId = $Linodes->updateWhmcsProduct($params, $data['id'], $csFieldDiskId);
				 $updateRootPassword = $Linodes->updateWhmcsProduct($params, $params['customfields']['root_password'], $csFieldRootPwId);
			}elseif($data['filesystem'] == 'swap'){
				$updateSwapId = $Linodes->updateWhmcsProduct($params, $data['id'], $csFieldSwapDiskId);
			}
		}
		$getConfigList = $Linodes->getConfigList($linodeId);
		foreach($getConfigList['data'] as $data){
			 $updateConfigkId = $Linodes->updateWhmcsProduct($params, $data['id'], $csFieldConfigId);
		}
        $status = 'success';
    } else {
        $linodeId = $params['customfields']['linode_id'];
        $status = 'success';
    }
	$getList = $Linodes->getList($linodeId);
	$ipAddress = $getList['ipv4'][0];
	 $updateIP = Capsule::table("tblhosting")->where("id",$params['serviceid'])->update(["dedicatedip"=>$ipAddress]);
    //$adminuser = $Linodes->getWhmcsAdmin();
    $command = "sendemail";
    $values["messagename"] = 'Server Created Welcome Email';
    $values["customvars"] = base64_encode(serialize(array("user_name" => $params['clientsdetails']['firstname'] . ' ' . $params['clientsdetails']['lastname'], "ip" => $ipAddress, "root_password" => $params['customfields']['root_password'])));
    $values["id"] = $params['serviceid'];
    $results = localAPI($command, $values, $adminuser);

    return $status;
}

function linodes_SuspendAccount($params) {
	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active')
        return 'Error: Module license is ' . $licenseStatus . '.';

    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'server (linode) id is missing.';
	
    $shutdownLinode = $Linodes->shutdownLinode($linodeId);

    if (isset($shutdownLinode['errors'])) {
        $status = 'ERRORCODE: ' . $shutdownLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $shutdownLinode['errors'][0]['reason'];
    } else {
        $status = 'success';
    }
    return $status;
}

function linodes_UnsuspendAccount($params) {
	
	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active')
        return 'Error: Module license is ' . $licenseStatus . '.';

    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'Server (linode) id is missing.';

    $rebootLinode = $Linodes->rebootLinode($linodeId, $params['customfields']['config_id']);

    if (isset($rebootLinode['errors'])) {
        $status = 'ERRORCODE: ' . $rebootLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $rebootLinode['errors'][0]['reason'];
    } else {
        $status = 'success';
    }
    return $status;
}

function linodes_TerminateAccount($params) {
    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'Server (linode) id is missing.';

    $Linodes = new Linodes($params);
    $deleteLinode = $Linodes->deleteLinode($linodeId);

    if (isset($deleteLinode['errors'])) {
        $status = 'ERRORCODE: ' . $deleteLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $deleteLinode['errors'][0]['reason'];
    } else {

        $getDomainList = $Linodes->getDomainList();

        if (!empty($getDomainList['data'])) {
            foreach ($getDomainList['data'] as $key => $value) {
                if ($value['domain'] == $params['configoption6']) {
                    $domainId = $value['id'];
                }
            }
        }
        $resourceId = $params['customfields']['resoucres_id'];
        if (!empty($domainId) && !empty($resourceId))
            $deleteDomainResources = $Linodes->deleteDomainResources($domainId, $resourceId);

        $csFiledLinodeId = $Linodes->checkCsfield('linode_id', $params['pid']);
        $csFieldRootPwId = $Linodes->checkCsfield('root_password', $params['pid']);
        $csFiledSwapDiskId = $Linodes->checkCsfield('swap_disk_id', $params['pid']);
        $csFiledDiskId = $Linodes->checkCsfield('main_disk_id', $params['pid']);
        $csFiledConfigId = $Linodes->checkCsfield('config_id', $params['pid']);
        $csFiledResourcesId = $Linodes->checkCsfield('resoucres_id', $params['pid']);
        $csFiledRdnsId = $Linodes->checkCsfield('reverse_dns', $params['pid']);

        $updatelinodeId = $Linodes->updateWhmcsProduct($params, '', $csFiledLinodeId);
        $updateRootPassword = $Linodes->updateWhmcsProduct($params, '', $csFieldRootPwId);
        $updateDiskId = $Linodes->updateWhmcsProduct($params, '', $csFiledDiskId);
        $updateSwapId = $Linodes->updateWhmcsProduct($params, '', $csFiledSwapDiskId);
        $updateConfigId = $Linodes->updateWhmcsProduct($params, '', $csFiledConfigId);
        $updateResourcesId = $Linodes->updateWhmcsProduct($params, '', $csFiledResourcesId);
        $updateRdnsId = $Linodes->updateWhmcsProduct($params, '', $csFiledRdnsId);

        try {
            Capsule::table('tblhosting')->where('id', $params['serviceid'])->update(['dedicatedip' => '']);
        } catch (\Exception $ex) {
            logActivity("couldn't update tblhosting: {$e->getMessage()}");
        }
        $status = 'success';
    }
    return $status;
}

function linodes_ChangePackage($params) {
	
	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active')
        return 'Error: Module license is ' . $licenseStatus . '.';

    $csFieldLinodeId = $Linodes->checkCsfield('linode_id', $params['pid']);

    if (empty($csFieldLinodeId))
        return 'Product custom filed "Linode Id" is required.';

    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'You have not Linode service yet.';

    //$result = $Linodes->getAddonSetting($params['pid']);

    $planId = $params['configoption3'];

	if ($Linodes->getList($linodeId)['status'] != 'offline') {
        $shutdownLinode = $Linodes->shutdownLinode($linodeId);
        while ($Linodes->getList($linodeId)['status'] == 'shutting_down') {
            sleep(1);
        }
    }

    $resizeLinode = $Linodes->resizeLinode($linodeId, $planId);

    if ($resizeLinode['errors'][0]['field'] != 0) {
        $status = 'ERRORCODE: ' . $resizeLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $resizeLinode['errors'][0]['reason'];
    } else {
        $configId = $params['customfields']['config_id'];
        $bootLinode = $Linodes->rebootLinode($linodeId, $configId);
        $status = 'success';
    }
    return $status;
}

function linodes_reboot($params) {

	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active')
        return 'Error: Module license is ' . $licenseStatus . '.';

    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'Server (linode) id is missing.';


    $rebootLinode = $Linodes->rebootLinode($linodeId, $params['customfields']['config_id']);
	
    if (isset($rebootLinode['errors'])) {
        $status = 'ERRORCODE: ' . $rebootLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $rebootLinode['errors'][0]['reason'];
    } else {
        $status = 'success';
    }
    return $status;
}

function linodes_shutdown($params) {

	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
    if ($licenseStatus != 'Active')
        return 'Error: Module license is ' . $licenseStatus . '.';

    $linodeId = $params['customfields']['linode_id'];

    if (empty($linodeId))
        return 'server (linode) id is missing.';

    $shutdownLinode = $Linodes->shutdownLinode($linodeId);

    if (isset($shutdownLinode['errors'])) {
        $status = 'ERRORCODE: ' . $shutdownLinode['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $shutdownLinode['erors'][0]['reason'];
    } else {
        $status = 'success';
    }
    return $status;
}

function linodes_AdminCustomButtonArray() {
    $buttonarray = array(
        "Reboot Server" => "reboot",
        "Shutdown Server" => "shutdown",
    );
    return $buttonarray;
}

function linodes_ClientAreaCustomButtonArray() {
    $buttonarray = array(
        "Reboot Server" => "reboot",
        "Shutdown Server" => "shutdown",
    );
    return $buttonarray;
}

function linodes_ClientArea($params) {

	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
	$licenseStatus = 'Active';
    if ($licenseStatus != 'Active')
        return '<font color="red">Error: Module license is ' . $licenseStatus . '.</font>';

    $linodeId = $params['customfields']['linode_id'];
    $configId = $params['customfields']['config_id'];
    $rootPw = $params['customfields']['root_password'];

    if (empty($linodeId)) {
        return '<font color="red">Error: Server id is missing, please contact with support.</font>';
        $error = 'You have not service yet.';
    } else{
		
        $lang = $Linodes->linode_getLang($params);

        if (isset($_POST['customaction']) && !empty($_POST['customaction'])) {
            if (file_exists(__DIR__ . '/ajax/ajax.php')) {
                include (__DIR__ . '/ajax/ajax.php');
                die();
			}
            exit();
        }

        $getList = $Linodes->getList($linodeId);
        $linodeList = $getList;

        $getDiskList = $Linodes->getDiskList($linodeId);
		//print '<pre>';print_r($linodeList);print_r($getDiskList);die();
		$useddisk = '';
		foreach($getDiskList['data'] as $disk){
			$useddisk += $disk['size'];
		}

        $getIpList = $Linodes->getIpList();

        if (empty($params['customfields']['reverse_dns']))
            $resDNS = $getIpList['data'][0]['rdns'];
        else
            $resDNS = $params['customfields']['reverse_dns'];

       $getConfigList = $Linodes->getConfigList($linodeId, $params['customfields']['config_id']);

        if (isset($getList['errors']))
            $error = 'ERRORCODE: ' . $getList['errors'][0]['field'] . ' | ERRORMESSAGE: ' . $getList['errors'][0]['reason'];

        $allTemplate = $Linodes->getTemplates();
        $tempArr = array();
        foreach ($allTemplate['data'] as $templateAl) {
            $tempArr[] = array('id' => $templateAl['id'], 'template' => $templateAl['label']);
        }
		$templatedetail = $Linodes->getTemplates($getList['image']);
	
	 // get location ........................
		$ip = $linodeList['ipv4']['0'];
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$locationLabel = $details->city.','.$details->country;
		
	// get SSH detail ...........
	//$username = $params['username'];
	$lishsshregion  = $Linodes->getlishsshgateway($locationLabel);
	$pid = $params['pid'];
	$rootPasswordDiv = $Linodes->getshowdiv($pid,"reset_root_password");
	$rebuildDiv = $Linodes->getshowdiv($pid,"rebuild");
	$ipListDiv = $Linodes->getshowdiv($pid,"ip_list");
	$addPrivateDiv = $Linodes->getshowdiv($pid,"add_private_ip");
	$addPublicDiv = $Linodes->getshowdiv($pid,"add_public_ip");
	$serverActivitylogDiv = $Linodes->getshowdiv($pid,"server_activity_log");
	$rescueDiv = $Linodes->getshowdiv($pid,"rescue");
	$rdnsDiv = $Linodes->getshowdiv($pid,"rdns");
	$backupDiv = $Linodes->getshowdiv($pid,"backup");
  }

    return array(
        'templatefile' => 'clientarea',
        'vars' => array(
            'serviceid' => $params['serviceid'],
            'moduledata' => $params,
            'linodeId' => $linodeId,
            'configId' => $params['customfields']['config_id'],
            'linodelist' => $linodeList,
            'disklist' => $getDiskList,
            'configlist' => $getConfigList,
            'linodeerror' => $error,
            'ipfromadmin' => $params['customfields']['reverse_dns'],
            'iplist' => $getIpList['data'][0],
            'ostemplates' => $tempArr,
            'rootpw' => $params['customfields']['root_password'],
            'language' => $lang,
            'rdns' => $RDNS,
			'usedDisk' => $useddisk,
			'templatedetail' => $templatedetail,
			'locationLabel' => $locationLabel,
			'lishsshregion' => $lishsshregion,
			'rootPasswordDiv' => $rootPasswordDiv,
			'rebuildDiv' => $rebuildDiv,
			'addPrivateDiv' => $addPrivateDiv,
			'addPublicDiv' => $addPublicDiv,
			'serverActivitylogDiv' => $serverActivitylogDiv,
			'rescueDiv' => $rescueDiv,
			'rdnsDiv' => $rdnsDiv,
			'ipListDiv' => $ipListDiv,
			'backupDiv' =>  $backupDiv
		)
	);
}

function linodes_AdminServicesTabFields($params) {

	$Linodes = new Linodes($params);
    $table = 'mod_linode_license';
    $selectData = Capsule::table($table)->first();
	$licenseStatus = $selectData->status;
	$licenseStatus = 'Active';
    if ($licenseStatus != 'Active') {
        $fieldsarray = array(
            "<strong>License error</strong>" => '<font color="red">Module license is ' . $licenseStatus . '.</font>',
        );
        return $fieldsarray;
    }

    $linodeId = $params['customfields']['linode_id'];

    $getList = $Linodes->getList($linodeId);

    $getIpList = $Linodes->getIpList();
    $getConfigList = $Linodes->getConfigList($linodeId, $params['customfields']['config_id']);

	$serverStatus = $getList['status'];

    if (empty($params['customfields']['reverse_dns']))
        $resDNS = $getIpList['data'][0]['rdns'];
    else
        $resDNS = $params['customfields']['reverse_dns'];

    if (empty($params['configoption7']))
        $params['configoption7'] = 'vm-';
    else
        $params['configoption7'] = $params['configoption7'];

    if (!empty($params['configoption7']) && !empty($params['configoption8'])) {
        $RDNS = $params['configoption7'] . $params['serviceid'] . '.' . $params['configoption8'];
        if ($RDNS != $getIpList['DATA'][0]['RDNS_NAME'])
            $rdnsHtml = '&nbsp;<font style="float: right;color: #ef5f1c;cursor: default;" title="RDNS status till pending. it will take some time to update.">Pending</font>';
    }
    $detailHtml = '<script src="../modules/servers/linodes/js/jquery.js"></script><link href="../modules/servers/linodes/css/style.css" rel="stylesheet"><div class="row" style="margin: 0!important;">
                    <div class="col-md-12 serverlist" id="serverlist">
                        <div class="tabbable ">
                            <div class="manage_tab_content">
                                <div class="tab_row"><strong>Status</strong><span id="linodeStatus">' . $serverStatus . ' &nbsp;<i title="Refresh" id="getServerDetail" onclick="getServerDetail();" class="fa fa-refresh" aria-hidden="true" style="cursor: pointer;float: right;line-height: 25px; color: #13a408;"></i></span></div>
                                <div class="tab_row"><strong>Name</strong><span id="linodeLabel">' . str_replace('linode', 'server', $getList['label']) . '</span></div>
                                <div class="tab_row"><strong>Datacenter</strong><span> ' . str_replace(' ', ', ', $params['customfields']['datacenter']) . '</span></div>
                                <div class="tab_row"><strong>IP Address</strong><span>' . $getIpList['data'][0]['address'] . '</span></div>
                                <div class="tab_row"><strong>Reverse DNS</strong><span>' . $resDNS . $rdnsHtml . '</span></div>
                                <div class="tab_row"><strong>OS </strong><span>' . str_replace(" Profile", "", $getConfigList['label']) . '</span></div>
                            </div>
                        </div>
                    </div>
                </div>';

    $logHtml = '<div class="row" style="margin: 0!important;">
                            <div class="col-md-12 serverlist1" id="serverlist1">
                                <div class="tabbable">
                                    <div class="manage_tab_content">
                                        <div class="manage_heading">
                                            <h2>Server activity log</h2>
                                        </div>
                                        <div class="tab-content">
                                            <div class="table-container clearfix">
                                                <table class="table table-list dataTable no-footer dtr-inline" role="grid">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Id</th>
                                                            <th>Action</th>
                                                            <th>Date</th>
                                                            <th>LABEL</th>
                                                            <th>Message</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="adminlogbody">';
    foreach ($getJobList['DATA'] as $key => $list) {
        if ($list['HOST_SUCCESS'] == '1')
            $sts = 'Success';
        else
            $sts = 'Pending';

        if (empty($list['HOST_MESSAGE']))
            $msg = '&nbsp;';
        else
            $msg = $list['HOST_MESSAGE'];

        $logHtml .= '<tr role="row" class="odd">
                        <td>' . $list['LINODEID'] . '</td>
                        <td>' . $list['ACTION'] . '</td>
                        <td>' . $list['ENTERED_DT'] . '</td>
                        <td>' . $list['LABEL'] . '</td>
                        <td>' . $msg . '</td>
                        <td><strong>' . $sts . '</strong></td>
                    </tr>';
    }
    $logHtml .= '</tbody>                                
                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
				
    if (!empty($getList))
        $detailHtml = $detailHtml;
    else
        $detailHtml = '<font color="#ff0000;">Detail Not Found.</font>';

    if (!empty($getJobList['data']))
        $logHtml = $logHtml;
    else
        $logHtml = '<font color="#ff0000;">Detail Not Found.</font>';
		$logHtml = '';
    if (isset($_POST['getserverDetail'])) {
        echo $detailHtml . $logHtml;
        exit();
    }
    if (isset($_POST['userid']) && !isset($_POST['getserverDetail'])) {
        echo $jquery = '<script>jQuery(document).ready(function(){
                getServerDetail();
            });</script>';
        $fieldsarray = array(
            "<strong>Server Detail</strong>" => $detailHtml . '<div id="ajaxresponse" style="display:none"></div>',
            "<strong>Log<strong>" => $logHtml . '<div id="ajaxresponse1" style="display:none"></div>'
        );
    }

    return $fieldsarray;
}

?>
