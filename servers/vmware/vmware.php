<?php

/* * ****************************************************************
 *  WGS VMware WHMCS Addon Module By whmcsglobalservices.com
 *  Copyright whmcsglobalservices, All Rights Reserved
 *
 *  Release: 01 May 2016
 *  WHMCS Version: v6/v7/v8
 *  Version: 4.0.2
 *  Update Date: 19 May, 2021
 *
 *  By WHMCSGLOBALSERVICES    https://whmcsglobalservices.com
 *  Contact                   info@whmcsglobalservices.com
 *
 *  This module is made under license issued by whmcsglobalservices.com
 *  and used under all terms and conditions of license.    Ownership of
 *  module can not be changed.     Title and copy of    module  is  not
 *  available to any other person.
 *
 *  @owner <whmcsglobalservices.com>
 *  @author <WHMCSGLOBALSERVICES>
 * ********************************************************** */

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

if (file_exists(__DIR__ . '/class/class.php'))
    require_once __DIR__ . '/class/class.php';

if (file_exists(__DIR__ . '/manage_cfields.php'))
    require_once __DIR__ . '/manage_cfields.php';

if (file_exists(__DIR__ . '/../../addons/vmware/classes/class.php'))
    require_once __DIR__ . '/../../addons/vmware/classes/class.php';

function vmware_MetaData()
{

    return array(
        'DisplayName' => 'WGS VMware',
    );
}

function vmware_ConfigOptions(array $params)
{

    global $whmcs;

    try {

        ini_set('default_socket_timeout', 900);

        set_time_limit(0);

        $pid = $whmcs->get_req_var("id");

        $productResult = Capsule::table('tblproducts')->where('id', $pid)->first();

        try {
            $addonModule = Capsule::table('tbladdonmodules')->where('module', 'vmware')->get();
        } catch (Exception $ex) {
            logActivity("Unable to get the data from DB table tbladdonmodules for Vmware addon module error: {$ex->getMessage()}");
        }

        if (count($addonModule) > 0) {
            $serverData = Capsule::table('mod_vmware_server')->orderBy('id', 'asc')->get();

            $count = count($serverData);

            $serverdropdown = [];

            foreach ($serverData as $kley => $serverData01) {
                $serverdropdown[$serverData01->id] = $serverData01->server_name;
                if ($kley == 0) {
                    $s_name = $serverData01->id;
                }

                // $serverdropdown .= $serverData01->server_name;
                // if ($key != $count - 1) {
                //     $serverdropdown .= ',';
                // }
            }
            //$serverdropdown = rtrim($serverdropdown, ',');

            if (count($productResult) > 0) {
                if ($productResult->configoption3 == "")
                    $sName = $s_name;
                else
                    $sName = $productResult->configoption3;

                $serverData = Capsule::table('mod_vmware_server')->where('server_name', $sName)->get();
                if (count($serverData) == 0)
                    $serverData = Capsule::table('mod_vmware_server')->where('id', $sName)->get();
                if (count($serverData) > 0) {

                    $WgsVmwareObj = new WgsVmware();

                    $WgsVmwareObj->vmware_includes_files();

                    $hideGuestOs = $hideCustomFieldGuestOs = $createConfigOption = $hideDcCustomField = $hideDcOpt = false;
                    if ($productResult->configoption1 == "" && $productResult->configoption3 == "") {
                        $createConfigOption = $hideCustomFieldGuestOs = false;
                    } elseif ($productResult->configoption1 != "" && $productResult->configoption3 != "") {
                        $createConfigOption = $hideCustomFieldGuestOs =  $hideDcCustomField = true;
                    } elseif ($productResult->configoption1 == "" && $productResult->configoption20 != "" && $productResult->configoption3 != "") {
                        $createConfigOption = true;
                        $hideGuestOs = true;
                        $hideCustomFieldGuestOs = false;
                        $hideDcCustomField = true;
                    } elseif ($productResult->configoption1 != "" && $productResult->configoption20 != "" && $productResult->configoption3 != "") {
                        $createConfigOption = true;
                        $hideCustomFieldGuestOs = true;
                        $hideDcCustomField = true;
                    } else {
                        $createConfigOption = false;
                        $hideCustomFieldGuestOs = false;
                        $hideDcCustomField = false;
                        $hideDcOpt = true;
                    }

                    try {
                        $getip = explode('://', $serverData[0]->vsphereip);
                        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);
                        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);
                        $hostOption = '';

                        $hostOptionsArr['none'] = "None";

                        $hosts = $vms->getAllHosts();

                        if ($hosts) {

                            foreach ($hosts as $hostsVal) {

                                $hostName = $hostsVal->name;

                                $hostOptionsArr[$hostsVal->reference->_] = $hostName;
                            }

                            $hostOption = $hostOptionsArr;
                        } else {
                            $hostOption = 'Not Found';
                        }
                        $datacenterArr = [];
                        if($productResult->configoption1 != "" || $productResult->configoption20 != ""){
                            $datacenter = $vms->list_datacenters();

                            if (is_array($datacenter) && !empty($datacenter)) {
                                foreach ($datacenter as $key => $value) {
                                    $datacenterArr[$value->reference->_] = $value->name;
                                }
                            }
                        }

                    } catch (Exception $e) {

                        $configarray = array(
                            "Connection couldn't be established" => array(
                                "Type" => "na",
                                "Description" => '<span style="color:#ff0000;">' . $e->getMessage() . '</span>'
                            )
                        );

                        logActivity($e->getMessage());

                        $connErrString = (string) $e->getMessage();

                        $position = strpos($connErrString, 'Object');

                        $connErr = substr($connErrString, 0, $position);

                        //return $configarray;
                    }
                    vmwareCreateProductConfigurableOption($pid, $createConfigOption, $productResult->configoption3, $productResult, $hideGuestOs, $datacenterArr, $hideDcOpt);   # Create product configurable options

                    $WgsVmwareObj->vmware_manageCustomFields($pid, $createConfigOption, $productResult->configoption2, $hideCustomFieldGuestOs, $hideDcCustomField);
                }

                if ($hostOption == 'Not Found' || $hostOption == '') {

                    if (!empty($productResult->configoption15))
                        $hostOption = $productResult->configoption15;
                }

                $ExistHostName = !empty($productResult->configoption15) ? $productResult->configoption15 : '';
            }



            $html = '<style>.tabletd td.fieldarea{width:35% !important;}</style>

            <script>

                jQuery(document).ready(function(){

                    if(jQuery("input[type=\"checkbox\"][name=\"packageconfigoption[1]\"]").prop("checked") && jQuery("input[type=\"checkbox\"][name=\"packageconfigoption[20]\"]").prop("checked") === false){

                        jQuery("input[name=\"packageconfigoption[4]\"],input[name=\"packageconfigoption[5]\"],input[name=\"packageconfigoption[6]\"],input[name=\"packageconfigoption[7]\"],input[name=\"packageconfigoption[8]\"],input[name=\"packageconfigoption[9]\"],input[name=\"packageconfigoption[10]\"],input[name=\"packageconfigoption[11]\"],input[name=\"packageconfigoption[16]\"],input[name=\"packageconfigoption[19]\"]").attr("readonly",true);

                    }

                    jQuery("input[type=\"checkbox\"][name=\"packageconfigoption[20]\"]").click(function(){

                        if(jQuery(this).prop("checked")===true){

                            jQuery("input[name=\"packageconfigoption[4]\"],input[name=\"packageconfigoption[5]\"],input[name=\"packageconfigoption[6]\"],input[name=\"packageconfigoption[7]\"],input[name=\"packageconfigoption[8]\"],input[name=\"packageconfigoption[9]\"],input[name=\"packageconfigoption[10]\"],input[name=\"packageconfigoption[11]\"],input[name=\"packageconfigoption[16]\"],input[name=\"packageconfigoption[19]\"]").attr("readonly",false);

                        }else if(jQuery("input[type=\"checkbox\"][name=\"packageconfigoption[1]\"]").prop("checked")===true){

                            jQuery("input[name=\"packageconfigoption[4]\"],input[name=\"packageconfigoption[5]\"],input[name=\"packageconfigoption[6]\"],input[name=\"packageconfigoption[7]\"],input[name=\"packageconfigoption[8]\"],input[name=\"packageconfigoption[9]\"],input[name=\"packageconfigoption[10]\"],input[name=\"packageconfigoption[11]\"],input[name=\"packageconfigoption[16]\"],input[name=\"packageconfigoption[19]\"]").attr("readonly",true);

                        }

                    });

                    var connError = "' . $connErr . '";

                    if(connError){

                        setTimeout(function(){

                            jQuery(".perror").remove();

                            jQuery("#tblModuleSettings").before("<p class=\'perror\' style=\'color:#ff0000;padding: 10px;background: rgba(255, 47, 0, 0.17);border-radius: 4px;\'>"+connError+"</p>");

                        },1000);

                    }

                    jQuery("input[type=\"checkbox\"][name=\'packageconfigoption[1]\']").click(function(){if(jQuery(this).prop("checked")===false)jQuery("input[type=\"checkbox\"][name=\"packageconfigoption[20]\"]").prop("checked",false);jQuery("input[name=\'packageconfigoption[1]\']").closest("form").submit();});

                    //jQuery("input[name=\'packageconfigoption[2]\']").click(function(e){ var exist = ""; if(jQuery(this).prop("checked")) exist = "on"; else exist = ""; checkDatacenter(exist);});

                    jQuery("select[name=\'packageconfigoption[3]\']").attr("id","vmware_server");

                    jQuery("select[name=\'packageconfigoption[3]\']").attr("onchange","getHosts();");

                    jQuery("select[name=\'packageconfigoption[3]\']").css("width","180px");

                    jQuery("input[name=\'packageconfigoption[1]\']").parent().parent().parent().closest("table").addClass("tabletd");

                        getHosts();

                    jQuery("select[name=\'packageconfigoption[15]\'] option").each(function(){

                        if(jQuery(this).val() == "' . $ExistHostName . '")

                            jQuery("select[name=\'packageconfigoption[15]\']").val(jQuery(this).val());

                        jQuery("select[name=\'packageconfigoption[15]\']").css("max-width","95%");

                    });

                });

                function getHosts(){

                    jQuery(".perror").remove();

                    jQuery("select[name=\'packageconfigoption[15]\']").html("<option selected value disabled>Loading...</option>");

                    jQuery.post("../modules/servers/vmware/ajax/ajaxpost.php", {"custom":"ajax","get":"hostlist","ajaxaction":"vmware_hosts","productid":' . (int) $pid . ' , "server_name":jQuery("select[name=\'packageconfigoption[3]\']").val()}, function(res){

                        var result = jQuery.parseJSON(res);

                        if(result.status == "error"){

                            jQuery("input[name=\'packageconfigoption[2]\']").parent().parent().parent().closest("table").before("<p class=\'perror\' style=\'color:#ff0000;padding: 10px;background: rgba(255, 47, 0, 0.17);border-radius: 4px;\'>"+result.msg+"</p>");

                            jQuery("select[name=\'packageconfigoption[15]\']").html("<option selected value disabled>Not Found</option>");

                            return false;

                        }

                        jQuery("select[name=\'packageconfigoption[15]\']").html(result.option);
                        setDiskTypeOptions();

                    });

                }
                jQuery("select[name=\'packageconfigoption[15]\']").on("change",function(){
                    setDiskTypeOptions();
                });

                function setDiskTypeOptions(){
                    var hostid
                    jQuery.post("../modules/servers/vmware/ajax/ajaxpost.php", {"custom":"ajax","get":"vmware_host_datastores","ajaxaction":"vmware_host_datastores","productid":' . (int) $pid . ' ,
                    "hostid":jQuery("select[name=\'packageconfigoption[15]\']").val() , "server_name":jQuery("select[name=\'packageconfigoption[3]\']").val()}, function(res){

                        var result = jQuery.parseJSON(res);

                        if(result.status == "error"){


                            vmstorage();
                            return false;

                        }else{
                            jQuery("#vmstorage").parent().attr("colspan","");
                            if(jQuery(".disktypelabel").length){
                                jQuery(".disktypelabel").remove();
                                jQuery(".disktypeselect").remove();
                            }
                            var html="<td class=\"fieldlabel disktypelabel\" width=\"20%\">Select Disk type</td><td class=\"fieldarea disktypeselect\" colspan=\"\"><select name=\"packageconfigoption[25]\" class=\"form-control select-inline \" >"+result.option+"</select> <a id=\"vmstorage\" style=\"text-decoration:none;\" href=\"javacsript::\" title=\"Select that specific host where you want to create VM.\"><img src=\"../modules/servers/vmware/images/info.gif\"></a></td>";
                            jQuery("select[name=\'packageconfigoption[15]\']").closest("tr").append(html);

                        }



                    });
                }
                function vmstorage(){

                    jQuery("#vmstorage").parent().attr("colspan","100%");

                    jQuery("#vmstorage").parent().find("select").css("width","450px","max-width","100%");

                    jQuery("#vmstorage").parent().find("select").css("max-width","100%");

                }


            </script>';



            $configarray = array(
                "allowadmin" => array(
                    "FriendlyName" => "User defined Configurable Options",
                    "Type" => "yesno",
                    "Description" => "{$html}<a style='text-decoration:none;' href='javacsript::' title='If you have enbaled it then module will allow user to manage resources like (RAM, CPU, HDD, etc) from order page.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "no",
                ),
                "vmprefix" => array(
                    "FriendlyName" => "Vm Name Prefix",
                    "Type" => "dropdown",
                    "Options" => "Whmcs,IP Address,Datacenter Name,ESXI Node",
                    "Description" => "Created Vm Name should be like (Prefix_CustomerID_ServiceID).",
                    "Default" => "Whmcs",
                ),
                "select_server" => array(
                    "FriendlyName" => "Select Server",
                    "Type" => "dropdown",
                    "Options" => $serverdropdown,
                    "Size" => "25",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='servers'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "",
                ),
                "memory_mb" => array(
                    "FriendlyName" => "Memory MB",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<font color='red'>*</font>&nbsp;<a style='text-decoration:none;' href='javacsript::' title='Size of a virtual machine RAM'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "1024",
                ),
                "numberofcpu" => array(
                    "FriendlyName" => "Number of CPU's",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<font color='red'>*</font>&nbsp;<a style='text-decoration:none;' href='javacsript::' title='Unit'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "1",
                ),
                "additional_ip" => array(
                    "FriendlyName" => "Additional IP's",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Set additional IP'><img src='../modules/servers/vmware/images/info.gif'></a><tr><td colspan='100%' style='text-align: center;background: #2162a3;color: #fff;'>Hard Disk Setup For create Vm (GB) </td></tr>",
                    "Default" => "",
                ),
                "disk_drives1" => array(
                    "FriendlyName" => "Disk Drive 1",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<font color='red'>*</font>&nbsp;<a style='text-decoration:none;' href='javacsript::' title='Size of a virtual machine disk space, in GB'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "20",
                ),
                "disk_drives2" => array(
                    "FriendlyName" => "Disk Drive 2",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Size of a virtual machine disk space, in GB'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "",
                ),
                "disk_drives3" => array(
                    "FriendlyName" => "Disk Drive 3",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Size of a virtual machine disk space, in GB'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "",
                ),
                "disk_drives4" => array(
                    "FriendlyName" => "Disk Drive 4",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Size of a virtual machine disk space, in GB'><img src='../modules/servers/vmware/images/info.gif'></a><tr><td colspan='100%' style='text-align: center;background: #2162a3;color: #fff;'>Manage Bandwidth </td></tr>",
                    "Default" => "",
                ),
                "bandwidth_limit" => array(
                    "FriendlyName" => "Bandwidth limit (GB)",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Set bandwidth limit, example (1,enter without unit)'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "1",
                ),
                "bandwidth_alert" => array(
                    "FriendlyName" => "Bandwidth alert",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Set bandwidth alert, example(85, without %)'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "90",
                ),
                "bandwidth_price" => array(
                    "FriendlyName" => "Over Bandwidth Price",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Set over usage bandwidth price /GB'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "",
                ),
                "billiable" => array(
                    "FriendlyName" => "Suspend on Over Usage",
                    "Type" => "yesno",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='By default module create the billable item on over usage'><img src='../modules/servers/vmware/images/info.gif'></a><tr><td colspan='100%' style='text-align: center;background: #2162a3;color: #fff;'>Select the destination host to create VM</td></tr>",
                    "Default" => "yes",
                ),
                "storage" => array(
                    "FriendlyName" => "Select Host",
                    "Type" => "dropdown",
                    "Options" => $hostOption,
                    "Size" => "25",
                    "Description" => "<a id='vmstorage' style='text-decoration:none;' href='javacsript::' title='Select that specific host where you want to create VM.'><img src='../modules/servers/vmware/images/info.gif'></a><script></script><tr><td colspan='100%' style='text-align: center;background: #2162a3;color: #fff;'>Additional Settings</td></tr>",
                    "Default" => "",
                ),
                "cpu_limit" => array(
                    "FriendlyName" => "CPU Limit",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "MHz <a style='text-decoration:none;' href='javacsript::' title='Set CPU Allocation Limit in MHz. Leave empty for unlimited.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "500",
                ),
                "console" => array(
                    "FriendlyName" => "Enable console (https)",
                    "Type" => "yesno",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable to run the console on https.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "no",
                ),
                "dhcp" => array(
                    "FriendlyName" => "Enable DHCP",
                    "Type" => "yesno",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable DHCP to skip the IP pool manager.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "",
                ),
                "snapshot" => array(
                    "FriendlyName" => "Snap Shot Limit",
                    "Type" => "text",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enter snap shot limit.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    "Default" => "1",
                ),
                "additional_resources" => array(
                    "FriendlyName" => "Allow configurable options as additional resources",
                    "Type" => "yesno",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='If you have enabled this option the you can use this product as pre-defined and configurable options as additional resources.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                ),
                "suspend_vm" => array(
                    "FriendlyName" => "Enable suspend VM on terminate",
                    "Type" => "yesno",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable suspend VM on terminate command run (By default module will terminate the VM on termination command run).'><img src='../modules/servers/vmware/images/info.gif'></a>",
                ),
                "create_vm" => array(
                    "FriendlyName" => "Crete VM on Cron",
                    "Type" => "yesno",
                    "Size" => "10",
                    "Default" => "yes",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable to create VM on cron job.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                ),
                "all_guest_os" => array(
                    "FriendlyName" => "Enable this option to show all Guest OS Family with Reistall VM",
                    "Type" => "yesno",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable this option to show all Guest OS Family with Reistall VM.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                ),
                "enable_icon" => array(
                    "FriendlyName" => "Enable icons",
                    "Type" => "yesno",
                    "Size" => "10",
                    "Description" => "<a style='text-decoration:none;' href='javacsript::' title='Enable icons for the clientarea while order a product.'><img src='../modules/servers/vmware/images/info.gif'></a>",
                    
                )
            );
            
        } else {

            $configarray = array(
                "Addon not installed" => array(
                    "Type" => "na",
                    "Description" => '<span style="color:#ff0000;">Addon module (Vmware) not installed, please activate Vmware addon module, please <a target="_blank" href="configaddonmods.php">click here</a>.</span>'
                )
            );
        }
    } catch (Exception $ex) {
        $configarray = array(
            "Module Error" => array(
                "Type" => "na",
                "Description" => '<span style="color:#ff0000;">Error ' . $ex->getMessage() . '</span>'
            )
        );
    }

    // echo '<pre>';
    // print_r($configarray);

    return $configarray;
}

function vmware_CreateAccount($params)
{
    if (!Capsule::Schema()->hasTable('mod_vmware_hosts_vms')) {
        return 'Oops something is wrong. Please go to "Addons" >> "WGS VMware" and click on "Update Module Setting" to update the module updates.';
    }
    

    $_LANG = vmware_getLang($params);

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $sid = $params['serviceid'];

    $status = $description = "";
    
    if ($customFieldVal['datacenter'] == '') {
        $vms = new vmware();
        $vms->storeVmwareLogs($sid, "", "Datacenter is missing", "Failed");
        return 'Error: Datacenter is missing';
    }
    if (empty($params['configoption1'])) {

        if (empty($params['configoption4'])) {

            return 'RAM (Memory MB) is missing';
        } elseif (empty($params['configoption5'])) {

            return 'CPU is missing';
        } elseif (empty($params['configoption7']) || $params['configoption7'] == 0) {

            return 'Hard disk is empty, add at least one hard disk';
        }
    }

    $dhcp = $params['configoption18'];

    $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

    $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
    if (count($serverData) == 0)
        $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

    if (count($serverData) == 0)
        return "vCenter server not found!";

    $getip = explode('://', $serverData[0]->vsphereip);

    $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

    $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

    $clone = true;
    
    $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->get();
    /*Remove it please as it is for testing only*/
    // $getResources=[];

    if (count($getResources) == 0) {
        $clone = false;

        $getResources = Capsule::table('mod_vmware_os_list')->where('server_id', $serverData[0]->id)->where('os_version', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->get();
    }

    $getResources = (array) $getResources[0];


    $fromtemplate = '';

    if (!empty($getResources['fromtemplate']))
        $fromtemplate = '1';

        
    $datacenter_obj = $vms->get_datacenter($customFieldVal['datacenter'])->toReference()->_;

    $osType = $customFieldVal['os_type'];

    $dataceter_name = $datacenter_obj;

    $guetOsVersion = $customFieldVal['os_version'];

    $ram = $customFieldVal['ram'];

    $cpu = $customFieldVal['cpu'];

    $hardDisk = $customFieldVal['hard_disk'];

    $diskPartition = $customFieldVal['hard_disk_partition'];

    $additionalIp = $customFieldVal['additional_ip'];

    $additionalIp = $additionalIp + 1;

    $server_name = $customFieldVal['server_name'];

    $memoryMB = $ram;
    $cpuMhz = $params['configoption16'];

    $cpuMhz = preg_replace("/[^0-9]+/", '', $cpuMhz);

    $numCPUS = $cpu;

    //$resource_pool_id = $getResources['resourcepool'];

    $defaultHost = $params['configoption15'];

    $getDatacenterHosts = $vms->list_datacenters_host($customFieldVal['datacenter']);
    if ($getDatacenterHosts) {
        $hostArr = [];
        foreach($getDatacenterHosts as $hostKey => $hostRes){
            foreach($hostRes as $hostData){
                if($hostData)
                $hostArr[] = $hostData->reference->_;
            }
        }
    }

    if ($defaultHost != 'none') {
        if ($vms->get_host_network($defaultHost)) {
            $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->where('host_id', $defaultHost)->where('disable', '0')->orderBy('priority', 'ASC')->get();
        }
    } else {
        if ($hostArr)
            $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->whereIn('host_id', $hostArr)->where('disable', '0')->orderBy('priority', 'ASC')->get();
        else
            $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->where('disable', '0')->orderBy('priority', 'ASC')->get();
    }
    
    if (count($GetHostSetting) == 0) {
        $slectedHost = $vms->get_host_parent($defaultHost)->name;
        return "Host {$slectedHost} is not exist in this datacenter {$customFieldVal['datacenter']}.";
    }

    $datastore_id = '';
    $hostFull = true;

    foreach ($GetHostSetting as $host) {
        $hostName = $hostsystem_name = $vms->get_host_parent($host->host_id)->name;

        $ipCount = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->count();
        if (empty($dhcp)) {
            if(Capsule::table('mod_vmware_ip_list')->where('forvm', $customFieldVal['vm_name'])->where('sid', $params['serviceid'])->count == 0){
                if($additionalIp > $ipCount)
                    return "No free IP found in Hostname:  {$hostsystem_name}, Datacnter:   {$customFieldVal['datacenter']}";
            }
        }
        // echo Capsule::table("mod_vmware_hosts_vms")->where('hostid', $host->host_id)->count();
        // echo '<br>';
        // echo $host->vm_num;
        // die;
        if (Capsule::table("mod_vmware_hosts_vms")->where('hostid', $host->host_id)->count() <= $host->vm_num) {
            $GetDsSetting = Capsule::table("mod_vmware_ds_setting")->where('host_id', $host->host_id)->where('disable', '0')->orderBy('priority', 'ASC')->get();
            foreach ($GetDsSetting as $DS) {
                $DsInfo = $vms->datastoreDetail($DS->ds_id);

                $dsFreeDisk = round($DsInfo->getSummary()->freeSpace / 1073741824, 2);
                if ((float) $hardDisk < (float) $dsFreeDisk) {
                    $datastore_id = $DS->ds_id;
                    $hostObj = $DS->host_id;
                    $getResources['network_adp'] = $host->network;
                    $hostFull = false;
                    break;
                }
            }
            if ($datastore_id)
                break;
        }
        //  else {
        //     //$getHostName = $vms->get_host_network($host->host_id);
        //     $hostName = $hostsystem_name;
        //     $hostFull = true;
        // }
    }
    if ($hostFull)
        return "No. of VM's limit has been exceeded with this host {$hostName}. If resources value is free then increase the No. Of VM's value from Provisioning setting";

    if (!$datastore_id) {
        $vms->storeVmwareLogs($sid, "", "No enough space for VM creation", 'Failed');
        return "No enough space for VM creation";
    }
    $hostsystem_name = $vms->get_host_parent($hostObj)->name;

    $ipListArr = array();
    if (empty($dhcp)) {
        if (!empty($additionalIp) && $additionalIp > 0) {
            $getExistingIp = [];
            Capsule::Schema()->table('mod_vmware_ip_list', function ($table) {
                if (!Capsule::Schema()->hasColumn('mod_vmware_ip_list', 'sid'))
                    $table->integer('sid')->nullable();
            });
            //$getExistingIp = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('forvm', $customFieldVal['vm_name'])->get();
            $getExistingIp = Capsule::table('mod_vmware_ip_list')->orWhere('forvm', $customFieldVal['vm_name'])->where('sid', $params['serviceid'])->get();
            
            if (count($getExistingIp) > 0) {
                $getIpQuery = $getExistingIp;
            } else {

                if (Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->count() > 0) {
                    $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                }
                // else {
                //     $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                // }
            }

            foreach ($getIpQuery as $ipList) {

                $ipList = (array) $ipList;

                $ipListArr[] = $ipList;
            }

            if (count($ipListArr) > 0) {

                if ($additionalIp > count($ipListArr)) {

                    return 'Available IP ' . count($ipListArr) . ',  please decrease the IP or contact to support';
                }
            }
        }

        $additionalIPs = [];
        foreach ($ipListArr as $ipKey => $additionalIP) {
            if ($ipKey != 0) {
                $additionalIPs[] = $additionalIP['ip'];
            }
        }

        if (count($ipListArr) == 0) {

            return 'Error: IP Not found in datacenter: "' . $customFieldVal['datacenter'] . '" and hostname: "' . $hostsystem_name . '"';
        }

        $networkIp = $ipListArr[0]['ip'];

        $netmask = $ipListArr[0]['netmask'];

        $gateway = $ipListArr[0]['gateway'];

        $dns = $ipListArr[0]['dns'];
        $rDNS = $ipListArr[0]['reversedns'];
  
        $macaddress = $ipListArr[0]['macaddress'];
        if (empty($customFieldVal['vm_name'])) {
            if ($params['configoption2'] == 'IP Address' && empty($dhcp))
                $newVmname = $networkIp . '_' . $params['userid'] . '_' . $params['serviceid'];

            elseif ($params['configoption2'] == 'Datacenter Name')
                $newVmname = str_replace(' ', '', $customFieldVal['datacenter']) . '_' . $params['userid'] . '_' . $params['serviceid'];

            elseif ($params['configoption2'] == 'ESXI Node')
                $newVmname = str_replace(' ', '', $hostsystem_name) . '_' . $params['userid'] . '_' . $params['serviceid'];

            elseif ($params['configoption2'] == 'Whmcs')
                $newVmname = 'whmcs_' . $params['userid'] . '_' . $params['serviceid'];
            else
                $newVmname = 'whmcs_' . $params['userid'] . '_' . $params['serviceid'];
        } else {
            $newVmname = $customFieldVal['vm_name'];
        }

        if ($rDNS != '') {
            $rDNSArr = explode('.', $rDNS, 2);
            $newVmname = $rDNSArr[0];
            $domain = $rDNSArr[1];
        }

        try {

            if ($params['domain'] == "") {
                $updateArr = [
                    'domain' => ($rDNS) ? $rDNS : $networkIp,
                    'dedicatedip' => $networkIp,
                    'notes' => implode(",", $additionalIPs)
                ];
            } else {
                $updateArr = [
                    'dedicatedip' => $networkIp,
                    'notes' => implode(",", $additionalIPs)
                ];
            }

            Capsule::table('tblhosting')
                ->where('id', $params['serviceid'])
                ->update($updateArr);
        } catch (\Exception $e) {

            logActivity("couldn't update tblhosting: {$e->getMessage()}");
        }
    } else {

        $networkIp = '';

        $netmask = '';

        $gateway = '';

        $dns = '';
        $rDNS = '';

        $macaddress = '';
        if (empty($customFieldVal['vm_name'])) {
            if ($params['configoption2'] == 'Datacenter Name')
                $newVmname = str_replace(' ', '', $customFieldVal['datacenter']) . '_' . $params['userid'] . '_' . $params['serviceid'];

            elseif ($params['configoption2'] == 'ESXI Node')
                $newVmname = str_replace(' ', '', $hostsystem_name) . '_' . $params['userid'] . '_' . $params['serviceid'];

            elseif ($params['configoption2'] == 'Whmcs')
                $newVmname = 'whmcs_' . $params['userid'] . '_' . $params['serviceid'];
            else
                $newVmname = 'whmcs_' . $params['userid'] . '_' . $params['serviceid'];
        } else {
            $newVmname = $customFieldVal['vm_name'];
        }
    }

    $vms->vmwareAssignIp($ipListArr, $newVmname, $dhcp, $params['serviceid']);
  

    $vmnameFieldId = $vms->vmwareGetCfId($params['pid'], 'vm_name');
    $vcenterServerFieldId = $vms->vmwareGetCfId($params['pid'], 'vcenter_server_name');

    $command = "updateclientproduct";

    $adminuser = '';

    $values["serviceid"] = $params['serviceid'];

    $values["customfields"] = base64_encode(serialize(array($vmnameFieldId => $newVmname, $vcenterServerFieldId => $serverData[0]->id)));

    $results = localAPI($command, $values, $adminuser);

    if ($params['configoption22'] == 'on') {

        if (Capsule::table("mod_vmware_cron_vm")->where('sid', $params['serviceid'])->count() == 0) {

            Capsule::table("mod_vmware_cron_vm")->insert(['sid' => $params['serviceid'], 'status' => '0']);

            $vms->storeVmwareLogs($sid, $newVmname, "VM creation request accepted. Waiting for cron run to complete it.", "success");
            return 'success';
        }
    }
    $info = $vms->get_vm_info($newVmname);
    $vmslist = $WgsVmwareObj->vmware_object_to_array($info);

    if (!empty($vmslist)) {
        return 'Error: Vmname "' . $newVmname . '" already exist!';
    }

    $network_adapters = array();

    $networkVl = explode("__", $getResources['network_adp']);

    foreach ($networkVl as $key => $networkValue) {
        $network_adapters[$key]['network'] = $networkValue;
    }
    $disk_drives = array();

    // if (!empty($params['configoption1'])) {

    if (count($diskPartition) == 0 && empty($diskPartition))
        $diskPartition = 1;

    for ($i = 1; $i <= $diskPartition; $i++) {
        $disk_drives[]['capacity'] = $hardDisk / $diskPartition;
    }
    // } else {
    //     $disk_drives[]['capacity'] = $params['configoption7'] + $params['configoption8'] + $params['configoption9'] + $params['configoption10'];
    // }
    $versionQuery = Capsule::table('mod_vmware_os_list')->select('isofile', 'os_version_id')->where('server_id', $serverData[0]->id)->where('os_version', $guetOsVersion)->where('datacenter', $customFieldVal['datacenter'])->first();
    if(count($versionQuery) == 0)
        $versionQuery = Capsule::table('mod_vmware_os_list')->select('isofile', 'os_version_id')->where('server_id', $serverData[0]->id)->where('os_version', $guetOsVersion)->first();
    if ($defaultHost != 'none')
        $versionQuery = Capsule::table('mod_vmware_os_list')->select('isofile', 'os_version_id')->where('server_id', $serverData[0]->id)->where('os_version', $guetOsVersion)->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->first();

    $operating_system_id = $versionQuery->os_version_id;

    //$isoQuery = Capsule::table('mod_vmware_os_list')->select('isofile')->where('server_id', $serverData[0]->id)->where('os_version_id', $operating_system_id)->first();

    $isofile = $versionQuery->isofile;

    $customFieldVal['vm_name'] = $newVmname;

    $sid = $params['serviceid'];

    $status = $description = "";
    if ($clone) {
        $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->first();
        if(count($getCloneVmName ) == 0)
            $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->first();
        if ($defaultHost != 'none')
            $getCloneVmName = Capsule::table('mod_vmware_temp_list')->where('os_family', $osType)->where('customname', $guetOsVersion)->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->first();
        
        $product_id = $getCloneVmName->product_key;

        $cloneVmName = $getCloneVmName->vmtemplate;
        $osPort = $getCloneVmName->port;

        $cloneVmPassword = ($WgsVmwareObj->wgsvmwarePwdecryption($getCloneVmName->sys_pw) == '') ? $getCloneVmName->sys_pw : $WgsVmwareObj->wgsvmwarePwdecryption($getCloneVmName->sys_pw);

        if ($getCloneVmName->autoconfig == 1)
            $autoConfiguration = true;
        else
            $autoConfiguration = false;

        $info = $vms->get_vm_info($cloneVmName);

        $cloneVminfo = $WgsVmwareObj->vmware_object_to_array($info);

        if (!$cloneVminfo) {
            return 'Error: Existing Vm or VM template "' . $cloneVmName . '" Not found.';
        }

        $cloneArr = [
            'templatename' => $cloneVmName,
            'existingpw' => $cloneVmPassword,
            'newVmname' => $newVmname,
            'networkIp' => $networkIp,
            'netmask' => $netmask,
            'gateway' => $gateway,
            'dns' => $dns,
            'rdns' => $rDNS,
            'macaddress' => $macaddress,
            'ApiServerName' => $ApiServerName,
            'server_id' => $serverData[0]->id,
            'hostsystem_name' => $hostsystem_name,
            'datastore_id' => $datastore_id,
            'memoryMB' => $memoryMB,
            'numCPUS' => $numCPUS,
            'network_adapters' => $network_adapters,
            'disk_drives' => $disk_drives,
            '_LANG' => $_LANG,
            'datacenter' => $customFieldVal['datacenter'],
            'dcobj' => $dataceter_name,
            'autoConfiguration' => $autoConfiguration,
            'cpuMhz' => $cpuMhz,
            'dhcp' => $dhcp,
            'fromtemplate' => $fromtemplate,
            'osType' => $osType,
            'guetOsVersion' => $guetOsVersion,
            'ipListArr' => $ipListArr,
            'product_id' => $product_id,
            'osport' => $osPort
        ];

        if ($osType == 'Linux' || $osType == 'Others') {
            try {
                $result = $vms->cloneLinuxVm($cloneArr, $params);
                if (Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->count() > 0) {
                    Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->update(['hostid' => $hostObj, 'vmname' => $newVmname]);
                } else {
                    Capsule::table("mod_vmware_hosts_vms")->insert(['sid' => $params['serviceid'], 'hostid' => $hostObj, 'vmname' => $newVmname]);
                }
                // $result = $vms->cloneLinuxVm($cloneVmName, $cloneVmPassword, $newVmname, $networkIp, $netmask, $gateway, $dns, $macaddress, $params['serviceid'], $params['userid'], $params['clientsdetails']['firstname'], $params['clientsdetails']['lastname'], $ApiServerName, $serverData[0]->id, $params, $hostsystem_name, $resource_pool_id, $datastore_id, $memoryMB, $numCPUS, $network_adapters, $disk_drives, $ipListArr, $_LANG, $dataceter_name, $autoConfiguration, $cpuMhz, $dhcp, $fromtemplate, $osType);
            } catch (Exception $ex) {
                $result = $ex->getMessage();
            }
        } else {
            try {
                $result = $vms->cloneWindowsVm($cloneArr, $params);
                if (Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->count() > 0) {
                    Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->update(['hostid' => $hostObj, 'vmname' => $newVmname]);
                } else {
                    Capsule::table("mod_vmware_hosts_vms")->insert(['sid' => $params['serviceid'], 'hostid' => $hostObj, 'vmname' => $newVmname]);
                }
                // $result = $vms->cloneWindowsVm($cloneVmName, $cloneVmPassword, $newVmname, $networkIp, $netmask, $gateway, $dns, $macaddress, $params['serviceid'], $params['userid'], $params['clientsdetails']['firstname'], $params['clientsdetails']['lastname'], $ApiServerName, $serverData[0]->id, $params['clientsdetails']['companyname'], $params, $hostsystem_name, $resource_pool_id, $datastore_id, $memoryMB, $numCPUS, $network_adapters, $disk_drives, $ipListArr, $_LANG, $dataceter_name, $autoConfiguration, $cpuMhz, $product_id, $dhcp, $fromtemplate, $osType);
            } catch (Exception $ex) {
                $result = $ex->getMessage();
            }
        }
    } else {
        try {
            $new_vm_name = $newVmname;
            $uname = $WgsVmwareObj->vmware_generateRandomString(7);
            $vmusername = $uname . $params['serviceid'];
            $datastore_path = $getResources['datastore'];
            $resource_pool_id = '';
            $vmData = [
                'vmname' => $new_vm_name,
                'dataceter_name' => $dataceter_name,
                'hostsystem_name' => $hostsystem_name,
                'resource_pool_id' => $resource_pool_id,
                'datastore_id' => $datastore_id,
                'memoryMB' => $memoryMB,
                'numCPUS' => $numCPUS,
                'network_adapters' => $network_adapters,
                'disk_drives' => $disk_drives,
                'vmusername' => $vmusername,
                'operating_system_id' => $operating_system_id,
                'isofile' => $isofile,
                'networkIp' => $networkIp,
                'server_name' => $server_name,
                'netmask' => $netmask,
                'gateway' => $gateway,
                'dns' => $dns,
                'rdns' => $rDNS,
                'macaddress' => $macaddress,
                '_LANG' => $_LANG,
                'ipListArr' => $ipListArr,
                'datacenter' => $customFieldVal['datacenter'],
                'datastore_path' => $datastore_path,
                'cpuMhz' => $cpuMhz,
                'dhcp' => $dhcp,
            ];
            $response_obj = $vms->create_vm($vmData, $params);
            // $response_obj = $vms->create_vm($new_vm_name, $dataceter_name, $hostsystem_name, $resource_pool_id, $datastore_id, $memoryMB, $numCPUS, $network_adapters, $disk_drives, $vmusername, $params['password'], $operating_system_id, $params['serviceid'], $params['userid'], $isofile, $networkIp, $server_name, $netmask, $gateway, $dns, $macaddress, $params, $ipListArr, $_LANG, $customFieldVal['datacenter'], $datastore_path, $cpuMhz, $dhcp);

            $result = $response_obj;
            if ($result == 'success' || $result == 'create_vm') {

                if (Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->count() > 0) {
                    Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->update(['hostid' => $hostObj, 'vmname' => $newVmname]);
                } else {
                    Capsule::table("mod_vmware_hosts_vms")->insert(['sid' => $params['serviceid'], 'hostid' => $hostObj, 'vmname' => $newVmname]);
                }
                $response_obj1 = $vms->vm_power_on($new_vm_name);

                logModuleCall("VMware", "Vm start", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj1['obj']));
            }
        } catch (Exception $e) {

            $result = $e->getMessage();
        }
    }

    if ($result == "success") {
        Capsule::table("mod_vmware_cron_vm")->where('sid', $params['serviceid'])->update(['status' => '1']);
        $description = " VM Succefully Created. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

        $status = "Success";
    }

    if ($result != "success") {
        $description = "VM Creation Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";
        $status = "Failed";
        try {
            Capsule::table('mod_vmware_ip_list')
                ->where('forvm', $newVmname)
                ->update(
                    [
                        'status' => '0',
                        'forvm' => '',
                        'sid' => ''
                    ]
                );
        } catch (\Exception $e) {
            logActivity("couldn't update mod_vmware_ip_list: {$e->getMessage()}");
        }

        try {
            if ($params['domain'] == "") {
                $updateArr = [
                    'domain' => ("" != $osPort)?$networkIp.":".$osPort:$networkIp,
                    'dedicatedip' => ("" != $osPort)?$networkIp.":".$osPort:$networkIp,
                ];
            } else {
                $updateArr = [
                    'dedicatedip' => ("" != $osPort)?$networkIp.":".$osPort:$networkIp,
                ];
            }

            Capsule::table('tblhosting')
                ->where('id', $params['serviceid'])
                ->update($updateArr);
        } catch (\Exception $e) {
            logActivity("couldn't update tblhosting: {$e->getMessage()}");
        }
    }

    $vms->storeVmwareLogs($sid, $newVmname, $description, $status);

    return $result;
}

function vmware_SuspendAccount($params)
{
    $WgsVmwareObj = new WgsVmware();
    $WgsVmwareObj->vmware_includes_files($params);
    $customFieldVal = vmwareGetCustomFiledVal($params);
    $new_vm_name = $customFieldVal['vm_name'];
    $sid = $params['serviceid'];
    $status = $description = "";
    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];
        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();
        if (count($serverData) == 0)
            return "vCenter server not found!";
        $getip = explode('://', $serverData[0]->vsphereip);
        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);
        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);
        if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState == 'poweredOff') {
            $vms->vm_power_on($new_vm_name);
        }
        $response_obj = $vms->vm_suspend($new_vm_name);

        logModuleCall("VMware", "suspend vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {
            $result = 'success';
            $description = "Succefully VM Suspended. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";
            $status = "Success";
        } elseif ($response_obj['state'] == 'Vm not found') {
            $result = $response_obj['state'];
            $description = "VM Suspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";
            $status = "Failed";
        } else {
            $result = $response_obj['state'];
            $description = "VM Suspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";
            $status = "Failed";
        }
    } catch (Exception $e) {
        $result = $e->getMessage();
        $description = "VM Suspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";
        $status = "Failed";
    }
    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);
    return $result;
}

function vmware_UnsuspendAccount($params)
{

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);



    $customFieldVal = vmwareGetCustomFiledVal($params);



    $new_vm_name = $customFieldVal['vm_name'];

    $sid = $params['serviceid'];

    $status = $description = "";

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

        $response_obj = $vms->vm_power_on($new_vm_name);

        logModuleCall("VMware", "unsuspend vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

            $result = "success";

            $description = "Succefully VM Un-suspended. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

            $status = "Success";
        } elseif ($response_obj['state'] == 'Vm not found') {

            $result = $response_obj['state'];

            $description = "VM Unsuspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        } else {

            $result = $response_obj['state'];

            $description = "VM Unsuspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }
    } catch (Exception $e) {

        $result = $e->getMessage();

        $description = "VM Unsuspend Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_TerminateAccount($params)
{
    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $sid = $params['serviceid'];

    $status = $description = "";

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

        if (empty($params['configoption21'])) {

            if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState != 'poweredOff') {

                $vms->vm_power_off($new_vm_name);
            }
            $response_obj = $vms->vm_destry($new_vm_name);

            logModuleCall("VMware", "terminate vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $description = " VM Succefully Terminated. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

                $status = "Success";

                $result = "success";
                if (Capsule::Schema()->hasTable('mod_vmware_hosts_vms'))
                    Capsule::table("mod_vmware_hosts_vms")->where('sid', $params['serviceid'])->delete();
                Capsule::table('mod_vmware_snapshot_counter')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->delete();

                Capsule::table('mod_vmware_pw_linux_vm')->where('vm_name', $new_vm_name)->where('sid', $params['serviceid'])->where('sid', $params['serviceid'])->delete();

                $vmNameFieldId = $vms->vmwareGetCfId($params['pid'], 'vm_name');

                $hostname_dc_cf_id = $vms->vmwareGetCfId($params['pid'], 'hostname_dc');

                $vncFieldId = $vms->vmwareGetCfId($params['pid'], 'vnc_detail');

                $macFieldId = $vms->vmwareGetCfId($params['pid'], 'mac_address');

                Capsule::table('mod_vmware_vm_ip')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->delete();
                Capsule::table("mod_vmware_reinstall_vm")->where('serviceid', $params['serviceid'])->delete();
                try {

                    $updatedStatus = Capsule::table('mod_vmware_ip_list')
                        ->where('forvm', $customFieldVal['vm_name'])
                        ->update(
                            [
                                'status' => '0',
                                'forvm' => '',
                            ]
                        );
                } catch (\Exception $e) {

                    logActivity("couldn't update mod_vmware_ip_list: {$e->getMessage()}");
                }

                try {
                    if ($params['domain'] == "") {
                        $updateArr = [
                            'domain' => "",
                            'dedicatedip' => "",
                        ];
                    } else {
                        $updateArr = [
                            'dedicatedip' => "",
                        ];
                    }

                    Capsule::table('tblhosting')
                        ->where('id', $params['serviceid'])
                        ->update($updateArr);
                } catch (\Exception $e) {

                    logActivity("couldn't update tblhosting: {$e->getMessage()}");
                }

                if ($params['configoption22'] == 'on') {

                    if (Capsule::table("mod_vmware_cron_vm")->where('sid', $params['serviceid'])->count() > 0) {

                        Capsule::table("mod_vmware_cron_vm")->where('sid', $params['serviceid'])->delete();
                    }
                }

                $adminQuery = Capsule::table('tbladmins')->select('id')->get();

                $admin = $adminQuery[0]->id;

                $command = "updateclientproduct";

                $adminuser = $admin;

                $values["serviceid"] = $params['serviceid'];
                $values['dedicatedip'] = "";
                $values["customfields"] = base64_encode(serialize(array($vmNameFieldId => '', $vncFieldId => '', $hostname_dc_cf_id => '', $macFieldId => '')));

                $results = localAPI($command, $values, $adminuser);
            } elseif ($response_obj['state'] == 'Vm not found') {

                $result = $response_obj['state'];

                $description = "VM Terminated Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            } else {

                $result = $response_obj['state'];

                $description = "VM Terminated Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        } elseif ($params['configoption21'] == 'on') {

            if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState == 'poweredOff') {

                $vms->vm_power_on($new_vm_name);
            }

            $response_obj = $vms->vm_suspend($new_vm_name);

            logModuleCall("VMware", "suspend vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

            if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

                $result = 'success';

                $description = "VM Successfully Suspended (On Terminate). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Success";
            } elseif ($response_obj['state'] == 'Vm not found') {

                $result = $response_obj['state'];

                $description = "VM Suspend Failed (On Terminate). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            } else {

                $result = $response_obj['state'];

                $description = "VM Suspend Failed (On Terminate). <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

                $status = "Failed";
            }
        }
    } catch (Exception $e) {

        $result = $e->getMessage();

        $description = "VM Terminated Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_ChangePackage($params)
{
    $_LANG = vmware_getLang($params);

    $sid = $params['serviceid'];

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);
    $dhcp = $params['configoption18'];

    try {

        $customFieldVal = vmwareGetCustomFiledVal($params);

        $newVmname = $customFieldVal['vm_name'];
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

        $datacenter_obj = $vms->get_datacenter($customFieldVal['datacenter'])->toReference()->_;

        $osType = $customFieldVal['os_type'];

        $dataceter_name = $datacenter_obj;

        $guetOsVersion = $customFieldVal['os_version'];

        $ram = $customFieldVal['ram'];

        $cpu = $customFieldVal['cpu'];

        $hardDisk = $customFieldVal['hard_disk'];

        $diskPartition = $customFieldVal['hard_disk_partition'];

        $additionalIp = $customFieldVal['additional_ip'];

        $clone = true;

        $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->get();

        if (count($getResources) == 0) {

            $clone = false;

            $getResources = Capsule::table('mod_vmware_os_list')->where('server_id', $serverData[0]->id)->where('os_version', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->get();
        }

        $getResources = (array) $getResources[0];

        $hostsystem_name = $getResources['hostname'];

        $defaultHost = $params['configoption15'];

        $getDatacenterHosts = $vms->list_datacenters_host($customFieldVal['datacenter']);
        if ($getDatacenterHosts) {
            $hostArr = [];
            foreach($getDatacenterHosts as $hostKey => $hostRes){
                foreach($hostRes as $hostData){
                    if($hostData)
                    $hostArr[] = $hostData->reference->_;
                }
            }
        }

        if ($defaultHost != 'none') {
            if ($vms->get_host_network($defaultHost)) {
                $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->where('host_id', $defaultHost)->where('disable', '0')->orderBy('priority', 'ASC')->get();
            }
        } else {
            if ($hostArr)
                $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->whereIn('host_id', $hostArr)->where('disable', '0')->orderBy('priority', 'ASC')->get();
            else
                $GetHostSetting = Capsule::table("mod_vmware_host_setting")->where('serverid', $serverData[0]->id)->where('dc_id', $datacenter_obj)->where('disable', '0')->orderBy('priority', 'ASC')->get();
        }
        if (count($GetHostSetting) == 0)
            return "Host not found.";
        $datastore_id = '';
        foreach ($GetHostSetting as $host) {
            $GetDsSetting = Capsule::table("mod_vmware_ds_setting")->where('host_id', $host->host_id)->where('disable', '0')->orderBy('priority', 'ASC')->get();
            foreach ($GetDsSetting as $DS) {
                $DsInfo = $vms->datastoreDetail($DS->ds_id);

                $dsFreeDisk = round($DsInfo->getSummary()->freeSpace / 1073741824, 2);
                if ((float) $hardDisk < (float) $dsFreeDisk) {
                    $datastore_id = $DS->ds_id;
                    $hostObj = $DS->host_id;
                    $getResources['network_adp'] = $host->network;
                    break;
                }
            }
            if ($datastore_id)
                break;
        }
        if (!$datastore_id) {
            $vms->storeVmwareLogs($sid, "", "No enough space for VM creation", 'Failed');
            return "No enough space for VM creation";
        }

        $hostsystem_name = $vms->get_host_parent($hostObj)->name;

        $ipListArr = array();

        if (empty($dhcp)) {

            if (!empty($additionalIp) && $additionalIp > 0) {
                if (Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->count() > 0) {
                    $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('hostname', $hostsystem_name)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                } else {
                    $getIpQuery = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('status', '0')->orderBy('id', 'asc')->inRandomOrder()->limit($additionalIp)->get();
                }

                foreach ($getIpQuery as $ipList) {
                    $ipList = (array) $ipList;
                    $ipListArr[] = $ipList;
                }

                if (count($ipListArr) > 0) {
                    if ($additionalIp > count($ipListArr)) {
                        return 'Available IP ' . count($ipListArr) . ',  please decrease the IP or contact with your support.';
                    }
                }
            }
        }

        // if ($params['configoption1'] == 'on') {
        //     if (!empty($params['configoption20']))
        //         $memoryMB = ($ram * 1024) + $params['configoption4'];
        //     else
        //         $memoryMB = $ram * 1024;
        // } else {

        //     $memoryMB = $ram;
        // }
        $memoryMB = $ram;
        $cpuMhz = $params['configoption16'];

        $cpuMhz = preg_replace("/[^0-9]+/", '', $cpuMhz);

        // $resource_pool_id = $getResources['resourcepool'];

        $numCPUS = $cpu;

        $network_adapters = array();

        $networkVl = explode("__", $getResources['network_adp']);

        foreach ($networkVl as $key => $networkValue) {
            $network_adapters[$key]['network'] = $networkValue;
        }
        if ($vms->get_vm_info($newVmname)->summary->runtime->powerState != 'poweredOff') {

            //$vms->vm_shut_down($newVmname);
            $vms->vm_power_off($newVmname);
            //sleep(50);
        }
        $response_obj = $vms->reconfigure_vm($newVmname, $datastore_id, $memoryMB, $numCPUS, $hardDisk, $params['serviceid'], $ipListArr, $cpuMhz, $dhcp);

        $result = $response_obj;

        $vms->vm_power_on($newVmname);
        // if ($result == 'success') {
        // }
    } catch (Exception $ex) {
        $result = $ex->getMessage();
    }

    return $result;
}

function vmware_AdminCustomButtonArray()
{

    $buttonarray = array(
        "Shutdown Server" => "shutdown",
        "Power Off" => "power_off",
        "Power On" => "power_on",
        "Reset OS" => "Reloados",
        "Send VM Welcome Email" => "SendEmail"
    );

    return $buttonarray;
}

function vmware_Reloados($params)
{

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $sid = $params['serviceid'];

    $status = $description = "";

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $_LANG = vmware_getLang($params);

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

        $response_obj = $vms->vm_reset($new_vm_name);

        logModuleCall("VMware", "reload os", array('name' => $vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

            $result = "success";

            $description = " VM Succefully Reset. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

            $status = "Success";
        } elseif ($response_obj['state'] == 'Vm not found') {

            $result = $response_obj['state'];

            $description = "VM Reset Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        } else {

            $result = $response_obj['state'];

            $description = "VM Reset Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }
    } catch (Exception $e) {

        logModuleCall("VMware", "reload os", array('name' => $new_vm_name), $e->getMessage());

        $result = $e->getMessage();

        $description = "VM Reset Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_power_off($params)
{
    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $_LANG = vmware_getLang($params);

    $sid = $params['serviceid'];

    $status = $description = "";

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);



        if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState == 'poweredOff') {

            return $new_vm_name . " already in powered off state";
        }



        $response_obj = $vms->vm_power_off($new_vm_name);

        logModuleCall("VMware", "poweroff vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

            $result = "success";

            $description = " VM Succefully Power Off. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

            $status = "Success";
        } elseif ($response_obj['state'] == 'Vm not found') {

            $result = $response_obj['state'];

            $description = "VM Power Off Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        } else {

            $result = $response_obj['state'];

            $description = "VM Power Off Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }
    } catch (Exception $e) {

        $result = $e->getMessage();

        $description = "VM Power Off Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_power_on($params)
{
    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $_LANG = vmware_getLang($params);

    $sid = $params['serviceid'];

    $status = $description = "";

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);



        if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState == 'poweredOn') {

            return $new_vm_name . " is already in powered On state";
        }

        $response_obj = $vms->vm_power_on($new_vm_name);

        logModuleCall("VMware", "poweron vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));

        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

            $result = "success";

            $description = " VM Succefully Power On. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

            $status = "Success";
        } elseif ($response_obj['state'] == 'Vm not found') {

            $result = $response_obj['state'];

            $description = "VM Power On Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        } else {

            $result = $response_obj['state'];

            $description = "VM Power On Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }
    } catch (Exception $e) {

        $result = $e->getMessage();

        $description = "VM Power On Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_shutdown($params)
{

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $sid = $params['serviceid'];

    $status = $description = "";

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

        if ($vms->get_vm_info($new_vm_name)->summary->runtime->powerState == 'poweredOff') {

            $description = "VM Shut Down Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: " . $new_vm_name . " already in powered off state";

            $status = "Failed";

            $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

            return $new_vm_name . " already in powered off state";
        }



        //        if ($vminfo['propSet']['1']['val']['guest']['toolsStatus'] == 'toolsNotInstalled') {
        //            return "Error: Cannot complete operation because VMware Tools is not running in this virtual machine.";
        //        }

        $response_obj = $vms->vm_shut_down($new_vm_name);

        logModuleCall("VMware", "shutdown vm", array('name' => $new_vm_name), $WgsVmwareObj->vmware_object_to_array($response_obj['obj']));



        if ($response_obj['state'] == 'success' || $response_obj['state'] == '') {

            $result = "success";

            $description = " VM Succefully Shut Down. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

            $status = "Success";
        } elseif ($response_obj['state'] != 'success' && $response_obj['state'] != '') {

            $result = $response_obj['state'];

            $description = "VM Shut Down Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        } else {

            $result = $response_obj['state'];

            $description = "VM Shut Down Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

            $status = "Failed";
        }
    } catch (Exception $e) {

        $result = $e->__toString();

        $description = "VM Shut Down Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: {$result}";

        $status = "Failed";
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $status);

    return $result;
}

function vmware_SendEmail($params)
{
    
    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);

    $sid = $params['serviceid'];

    $vms = new vmware();

    $customFieldVal = vmwareGetCustomFiledVal($params);

    $new_vm_name = $customFieldVal['vm_name'];

    $ipListArr = Capsule::table('mod_vmware_ip_list')->where('forvm', $new_vm_name)->where('status', '2')->get();

    $_LANG = vmware_getLang($params);
    $vm_additional_network = '';
    if (count($ipListArr) > 0) {
        $vm_additional_network = '<p>' . $_LANG['vm_email_additionaliptext'] . '</p><p>';
        foreach ($ipListArr as $ipKey => $additionalIP) {
            $additionalIP = (array) $additionalIP;
            $vm_additional_network .= $additionalIP['ip'] . ' <br/>';
        }
        $vm_additional_network .= '</p>';
    }
    $ram = ($customFieldVal['ram'] >= 1024) ? round(($customFieldVal['ram'] / 1024), 2) . ' GB' : $customFieldVal['ram'] . ' MB';
    $numCPUs = $customFieldVal['cpu'];
    $guetOsVersion = $customFieldVal['os_version'];
    $values["messagename"] = 'VMware Welcome Email';
    $values["customvars"] = base64_encode(serialize(array("vmname" => $new_vm_name, "vm_os" => $guetOsVersion, "vm_ram" => $ram, "vm_cpu" => $numCPUs, "vm_hdd" => $customFieldVal['hard_disk'] . ' GB', "vm_additional_network" => $vm_additional_network)));
    $adminuser = '';

    $command = "SendEmail";

    $values["id"] = $params['serviceid'];

    $results = localAPI($command, $values, $adminuser);

    if ($results['result'] == "success") {

        $description = "Server Detail Mail successfully sent. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>";

        $logStatus = "Success";

        $result = "success";
    } else {

        $description = "Server Detail Mail Failed. <a href=\"clientsservices.php?id={$sid}\" target='_blank'>Service ID: {$sid}</a>, Error: " . $results['message'];

        $logStatus = "Failed";

        $result = "Error: " . $results['message'];
    }

    $vms->storeVmwareLogs($sid, $new_vm_name, $description, $logStatus);

    return $result;
}

function vmware_AdminServicesTabFields($params)
{

    global $whmcs;

    $WgsVmwareObj = new WgsVmware();

    $WgsVmwareObj->vmware_includes_files($params);
    $_LANG = vmware_getLang($params);

    $tabs = array(
        "power" => "Power",
        "pause" => "Pause",
        "softreboot" => "Soft Reboot",
        "hardreboot" => "Hard Reboot",
        "reinstall" => "Re-install Vm",
        "mount" => "Mount",
        "upgradevmtool" => "Upgrade VM Tool",
        "snapshot" => "Snapshot",
        "removesnapshot" => "Remove Snapshot",
        "revertsnapshot" => "Revert Snapshot",
        "migrate" => "Uncheck to enable (Migrate Vm)",
    );

    $accessTab = array();

    $data = Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->get();

    $settingArr = explode(',', $data[0]->setting);

    $accessTab = array();

    foreach ($settingArr as $setting) {

        $accessTab[trim($setting)] = $tabs[trim($setting)];
    }
    $html = '<tr><td width="20%" class="fieldlabel"></td><td class="fieldarea" colspan="100%">' . $_LANG['vm_tab_access'] . '</td></tr>';

    foreach ($tabs as $key => $tab) {

        $html .= '<tr><td width="20%" class="fieldlabel">' . $tab . '</td><td class="fieldarea" colspan="100%"><input type="checkbox" name="tabaccess[' . $key . ']" value="1"';

        if (isset($accessTab[$key]))
            $html .= ' checked="checked"';

        $html .= '></td></tr>';
    }

    try {
        $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

        $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
        if (count($serverData) == 0)
            $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

        if (count($serverData) == 0)
            return "vCenter server not found!";

        $getip = explode('://', $serverData[0]->vsphereip);

        $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

        $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

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

        if (count($info) > 0) {

            $hostObj = $info->runtime->host->reference->_;

            $host = $vms->get_host_resources($hostObj);

            $hostName = $host->name;

            if ($hostName) {
                $hostFieldId = $vms->vmwareGetCfId($params['pid'], 'hostname_dc');

                $hostFieldData = $customFieldVal['hostname_dc'];

                $hostFieldArr = explode('&&', html_entity_decode($hostFieldData));

                if (!empty($hostFieldArr['1']))
                    $dc = $hostFieldArr['1'];
                else
                    $dc = '';

                $command = "updateclientproduct";

                $adminuser = $admin;

                $values["serviceid"] = $params['serviceid'];

                if (!empty($hostName) && !empty($params['serviceid'])) {

                    $values["customfields"] = base64_encode(serialize(array($hostFieldId => $hostName . '&&' . $dc)));

                    $results = localAPI($command, $values, $adminuser);
                }
            }
        }

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
        $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->where('datacenter', $customFieldVal['datacenter'])->get();

        $osPort = '';
        if($getResources[0]->port != '')
            $osPort = $getResources[0]->port;
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
        if (count($vmData) > 0) {

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
            } else
                $status = ucfirst($vmData['runtime']['powerState']);



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



            $ip = array();

            foreach (Capsule::table('mod_vmware_ip_list')->where('forvm', $vm_name)->get() as $additional) {
                $additional = (array) $additional;
                if("" != $additional['port'])
                    $ip[] = $additional['ip'].":".$additional['ip'];
                else
                $ip[] = $additional['ip'];
            }

            $noOfIps = count($ip);

            $ips = implode(', ', $ip);

            $detailHtml = '<script src="../modules/servers/vmware/js/scripts.js"></script><link href="../modules/servers/vmware/css/style.css" rel="stylesheet"><div class="row" style="margin: 0!important;">

                    <div class="col-md-12 serverlist" id="serverlist">

                        <div class="tabbable ">

                            <div class="manage_tab_content">';

            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_name'] . '</strong><span>' . $vm_name . ' &nbsp;<i title="Refresh" id="getServerDetail" onclick="getServerDetail(this);" class="fa fa-sync" aria-hidden="true" style="cursor: pointer;float: right;line-height: 25px; color: #13a408;"></i></span></div>

                                <div class="tab_row"><strong>' . $_LANG['vm_power_state'] . '</strong><span class="powerstatus">' . $status . ' &nbsp;</span></div>';

            if (count($vmIps) > 0 && !empty($vmIps))
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_ip_address'] . '</strong><span>' . implode(', ', $vmIps) . ' &nbsp;</span></div>';
            if($customFieldVal['os_type'] == 'Linux')
                $portText = "SSH Port";
            elseif($customFieldVal['os_type'] == 'Windows')
                $portText = "RDP Port";

            if($osPort)
                $detailHtml .= '<div class="tab_row"><strong>' . $portText . '</strong><span>' . $osPort . ' &nbsp;</span></div>';
            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_dedecated_os'] . '</strong><span>' . $vmData['config']['guestFullName'] . ' &nbsp;</span></div>';

            if (isset($vmData['guest']['hostName']) && !empty($vmData['guest']['hostName']))
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_guest_host_name'] . '</strong><span>' . $vmData['guest']['hostName'] . ' &nbsp;</span></div>';

            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_hostname'] . '</strong><span>' . $vms->get_host_resources($vmData['runtime']['host'])->name . ' &nbsp;</span></div>';
            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_tools_status'] . '</strong><span>' . $toolStatus . ' &nbsp;</span></div>';

            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_tools_version'] . '</strong><span>' . $toolVStatus . ' &nbsp;</span></div>';

            if (!empty($task))
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_recent_task'] . '</strong><span>' . $task . ' &nbsp;</span></div>';

            if ($percentageMem > 100)
                $percentageMem = '100';
            else
                $percentageMem = $percentageMem;

            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_memory_size'] . '</strong><span>' . $usedMemory . '/' . $memory . ' MB ' . '(' . $percentageMem . '%) <div class="percentagediv"><div class="percentage_inr ' . $class . '" style="width:' . $percentageMem . '%"></div></div> &nbsp;</span></div>';

            if ($vmData['runtime']['maxMemoryUsage'])
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_memory_usage'] . '</strong><span>' . $vmData['runtime']['maxMemoryUsage'] . ' MB &nbsp;</span></div>';

            $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_number_cpu'] . '</strong><span>' . $vmData['config']['numCpu'] . ' &nbsp;</span></div>';

            if ($vmData['runtime']['maxCpuUsage'])
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_cpu_usage'] . '</strong><span>' . $vmData['runtime']['maxCpuUsage'] . ' MHz &nbsp;</span></div>';

            $detailHtml .= $uptime;

            if ($noOfIps > 0)
                $detailHtml .= '<div class="tab_row"><strong>' . $_LANG['vm_number_ip'] . '</strong><span>' . $noOfIps . ',&nbsp;&nbsp;(' . $ips . ') &nbsp;</span></div>';

            $detailHtml .= '</div>

                            </div>

                        </div>

                    </div>';
        } else {

            $detailHtml = '<font color="red">Vm not found</font>';
        }
    } catch (Exception $ex) {

        $detailHtml = '<font color="red">' . $ex->getMessage() . '</font>';
    }



    if (isset($_POST['getserverDetail'])) {

        echo $detailHtml;

        exit();
    }



    if (!isset($_POST['getserverDetail'])) {

        echo $jquery = '<script>jQuery(document).ready(function(){

                getServerDetail(this);

            })</script>';

        $fieldsarray = array(
            '' => $html,
            '<strong>Server Detail</strong>' => $detailHtml . '<div id="ajaxresponse" style="display:none"></div>'
        );
    }

    return $fieldsarray;
}

function vmware_AdminServicesTabFieldsSave($params)
{

    global $whmcs;

    $row = Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count();

    $setting = '';

    $tabaccess = $whmcs->get_req_var("tabaccess");

    if (!empty($tabaccess)) {

        foreach ($tabaccess as $key => $value) {

            $setting .= $key . ', ';
        }

        $setting = substr($setting, 0, -1);



        $values = [
            'setting' => $setting,
            'uid' => $params['userid'],
            'sid' => $params['serviceid'],
        ];

        if ($row == 0) {

            try {

                Capsule::table('mod_vmware_settings')->insert($values);
            } catch (Exception $ex) {

                logActivity("could't insert into table mod_vmware_settings error: {$ex->getMessage()}");
            }
        } else {

            try {

                Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->update($values);
            } catch (Exception $ex) {

                logActivity("could't update into table mod_vmware_settings error: {$ex->getMessage()}");
            }
        }
    } else {

        $values = [
            'setting' => $setting,
            'uid' => $params['userid'],
            'sid' => $params['serviceid'],
        ];

        if ($row > 0) {

            try {

                Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->update($values);
            } catch (Exception $ex) {

                logActivity("could't update into table mod_vmware_settings error: {$ex->getMessage()}");
            }
        }
    }

    try {

        if (empty($params['customfields']['vnc_detail']) && empty($params['customfields']['hostname_dc'])) {

            $WgsVmwareObj = new WgsVmware();

            $WgsVmwareObj->vmware_includes_files($params);
            $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

            $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
            if (count($serverData) == 0)
                $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

            if (count($serverData) == 0)
                return "vCenter server not found!";

            $getip = explode('://', $serverData[0]->vsphereip);



            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

            $customFieldVal = vmwareGetCustomFiledVal($params);



            $vm_name = $customFieldVal['vm_name'];

            $info = $vms->get_vm_info($vm_name);

            if (count($info) > 0) {

                $hostObj = $info->runtime->host->reference->_;

                $host = $vms->get_host_resources($hostObj);

                $hostName = $host->name;

                //                if ($info->summary->runtime->powerState != 'poweredOff') {
                //                    $response_obj = $vms->vm_power_off($vm_name, true);
                //                }

                if (!empty($hostName)) {

                    $response = $vms->reconfigureExistingVm($vm_name, $params['serviceid'], $params['pid'], $hostName, $customFieldVal['datacenter']);

                    if ($response == 'success') {

                        $row = Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->count();

                        $setting = 'migrate';

                        $uid = $whmcs->get_req_var("user");

                        $settingValues = [
                            'setting' => $setting,
                            'uid' => $uid,
                            'sid' => $results['productids'],
                        ];

                        if ($row == 0) {

                            try {

                                Capsule::table('mod_vmware_settings')->insert($settingValues);
                            } catch (Exception $ex) {

                                logActivity("could't insert into table mod_vmware_settings error: {$ex->getMessage()}");
                            }
                        } else {

                            try {

                                Capsule::table('mod_vmware_settings')->where('uid', $uid)->where('sid', $results['productids'])->update($settingValues);
                            } catch (Exception $ex) {

                                logActivity("could't update into table mod_vmware_settings error: {$ex->getMessage()}");
                            }
                        }
                    } else {

                        logActivity("update vmware setting service id: {$params['serviceid']} error: {$response}");
                    }
                }
            }
        }
    } catch (Exception $ex) {

        logActivity("update vmware setting service id: {$params['serviceid']} error: {$ex->getMessage()}");
    }
}

function vmware_ClientArea($params)
{
    global $whmcs, $CONFIG;

    try {

        $WgsVmwareObj = new WgsVmware();

        $WgsVmwareObj->vmware_includes_files($params);

        $_LANG = vmware_getLang($params);

        if ($params['configoption22'] == 'on') {
            $vmCheck = Capsule::table("mod_vmware_cron_vm")->where('sid', $params['serviceid'])->where('status', '0')->count();
            if ($vmCheck == 1)
                return '<p style="background: #0080001f;color: green; padding: 20px; font-size: 14px;font-weight: 500;">' . $_LANG['vm_increation'] . '</p>';
        }

        $existData = Capsule::table("mod_vmware_reinstall_vm")->where('serviceid', $params['serviceid'])->where('status', '0')->first();
        if ($existData)
            return '<p style="background: #0080001f;color: green; padding: 20px; font-size: 14px;font-weight: 500;">' . $_LANG['vm_in_reinstattion'] . '</p>';


        if (!isset($_POST['appajaxaction']) && empty($_POST['appajaxaction'])) {

            $system_url = $CONFIG['SystemURL'];

            $consoleUrl = $system_url . '/modules/servers/vmware/console.php';

            //            $http = $params['configoption17'];
            //            if (!empty($http)) {
            //                $consoleUrl = $system_url . '/modules/servers/vmware/console.php?encrypt=0';
            //            }

            $page_url = $system_url . '/clientarea.php?action=productdetails&id=' . $params['serviceid'];

            $customFieldVal = vmwareGetCustomFiledVal($params);

            $newVmname = $customFieldVal['vm_name'];

            $osType = $customFieldVal['os_type'];

            $dataceter_name = $customFieldVal['datacenter'];

            $server_name = $customFieldVal['server_name'];

            $guetOsVersion = $customFieldVal['os_version'];

            $ram = $customFieldVal['ram'];

            $cpu = $customFieldVal['cpu'];

            $hardDisk = $customFieldVal['hard_disk'];

            $diskPartition = $customFieldVal['hard_disk_partition'];

            $additionalIp = $customFieldVal['additional_ip'];

            $additionalIp = $additionalIp + 1;

            $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name'] : $params['configoption3'];

            $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
            if (count($serverData) == 0)
                $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();

            if (count($serverData) == 0)
                return "vCenter server not found!";

            $esxi = $serverData[0]->esxi;

            $getip = explode('://', $serverData[0]->vsphereip);

            $vm_name = $newVmname;

            $ajaxaction = $whmcs->get_req_var("ajaxaction");

            $custom = $whmcs->get_req_var("custom");

            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, html_entity_decode($decryptPw));

            //            print_r($vms->createTicketToken($vm_name));

            $info = $vms->get_vm_info($vm_name);

            $isoFiles = '';

            $count = 0;

            $ajaxaction = $whmcs->get_req_var("ajaxaction");

            $sid = $params['serviceid'];

            $status = $description = "";

            $defaultHost = $params['configoption15'];
            if (!empty($ajaxaction)) {

                if ($ajaxaction == 'viewgraph') {

                    include('bandwidth.php');

                    die();
                } elseif ($ajaxaction == 'disks' || $ajaxaction == 'softreboot' || $ajaxaction == 'hardreboot' || $ajaxaction == 'poweroff' || $ajaxaction == 'poweron' || $ajaxaction == 'mount' || $ajaxaction == 'unmount' || $ajaxaction == 'paused' || $ajaxaction == 'unpaused' || $ajaxaction == 'upgrade' || $ajaxaction == 'createsnapshot' || $ajaxaction == 'createvm' || $ajaxaction == 'detail' || $ajaxaction == 'snapshot_list' || $ajaxaction == 'snapshot_update' || $ajaxaction == 'revert_latest_sp' || $ajaxaction == 'remove_all_sp' || $ajaxaction == 'btns' || $ajaxaction == 'delete_multi' || $ajaxaction == 'getoslist' || $ajaxaction == 'reinstallvm' || $ajaxaction == 'migrate' || $ajaxaction == 'getdchost' || $ajaxaction == 'getoslist_and_id' || $ajaxaction == 'changevmpw') {

                    if (file_exists(__DIR__ . '/ajax/ajaxpost.php')) {

                        include(__DIR__ . '/ajax/ajaxpost.php');

                        die();
                    }
                }

                exit();
            }

            $vmslist = $WgsVmwareObj->vmware_object_to_array($info);

            if (count($info) > 0) {

                $hostObj = $info->runtime->host->reference->_;

                $host = $vms->get_host_resources($hostObj);

                $hostName = $host->name;

                if ($hostName) {

                    $hostFieldId = $vms->vmwareGetCfId($params['pid'], 'hostname_dc');

                    $hostFieldData = $customFieldVal['hostname_dc'];

                    $hostFieldArr = explode('&&', html_entity_decode($hostFieldData));

                    if (!empty($hostFieldArr['1']))
                        $dc = $hostFieldArr['1'];
                    else
                        $dc = '';

                    $command = "updateclientproduct";

                    $adminuser = $admin;

                    $values["serviceid"] = $params['serviceid'];

                    if (!empty($hostName) && !empty($params['serviceid'])) {

                        $values["customfields"] = base64_encode(serialize(array($hostFieldId => $hostName . '&&' . $dc)));

                        $results = localAPI($command, $values, $adminuser);
                    }

                    $customFieldVal['hostname_dc'] = $hostName . '&&' . $dc;
                }
            }

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

            if (count($vminfo) == 0) {

                return '<font color="red">Error: Vm not found.</font>';
            }

            $debug = $whmcs->get_req_var("debug");
            $dcOptions = '<option value="">' . $_LANG['vm_migrate_select_dc'] . '</option>';

            $datacenter = $vms->list_datacenters();

            $dCenters = $WgsVmwareObj->vmware_object_to_array($datacenter[0]);

            foreach ($datacenter as $key => $value) {

                if ($value->name == $customFieldVal['datacenter'])
                    $select = 'selected="selected"';
                else
                    $select = '';

                $dcOptions .= '<option value="' . $value->name . '" ' . $select . '>' . ucfirst($value->name) . '</option>';
            }

            $netWorkDetail = Capsule::table('mod_vmware_ip_list')->where('server_id', $serverData[0]->id)->where('datacenter', $customFieldVal['datacenter'])->where('forvm', $vm_name)->get();

            $netWorkDetail = (array) $netWorkDetail[0];

            if ($vminfo['propSet'])
                $vmData = $vminfo['propSet'][1]['val'];
            else
                $vmData = $vminfo[1]['val'];

            $memory = $vmData['config']['memorySizeMB'];

            $usedMemory = $vmData['quickStats']['guestMemoryUsage'];

            if ($usedMemory == '')
                $usedMemory = 0;

            $percentageMem = round((($usedMemory / $memory) * 100), 2);

            if ($percentageMem < 30)
                $class = 'green';

            elseif ($percentageMem < 70)
                $class = 'moderate';

            elseif ($percentageMem > 70)
                $class = 'high';

            $os_option = '';

            $cloneOsListQryArr = array();

            $iso_osListQryArr = array();

            $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->where('datacenter', $customFieldVal['datacenter'])->get();

            if(count($cloneOsListQry) == 0)
                $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->get();
            if ($defaultHost != 'none') {
                $hostsystem_name = $vms->get_host_parent($defaultHost)->name;
                $cloneOsListQry = Capsule::table('mod_vmware_temp_list')->select('customname')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->get();
            }

            $i = 0;

            foreach ($cloneOsListQry as $key => $os_list) {
                $cloneOsListQryArr[$key] = $os_list->customname;
            }
            $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->where('datacenter', $customFieldVal['datacenter'])->get();
            if(count($iso_osListQry) == 0)
                $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->get();
            if ($defaultHost != 'none') {
                $hostsystem_name = $vms->get_host_parent($defaultHost)->name;
                $iso_osListQry = Capsule::table('mod_vmware_os_list')->select('os_version')->where('server_id', $serverData[0]->id)->where('os_family', $osType)->where('status', '')->where('datacenter', $customFieldVal['datacenter'])->where('hostname', $hostsystem_name)->get();
            }

            foreach ($iso_osListQry as $key => $os_list) {

                $iso_osListQryArr[$key] = $os_list->os_version;
            }

            $osListQry = array_unique(array_merge($cloneOsListQryArr, $iso_osListQryArr));

            foreach ($osListQry as $os_list) {

                if ($guetOsVersion == $os_list)
                    $sel = 'selected="selected"';
                else
                    $sel = '';

                $os_option .= '<option ' . $sel . ' value="' . $os_list . '">' . $os_list . '</option>';
            }
            $accessTab = array();

            $data = Capsule::table('mod_vmware_settings')->where('uid', $params['userid'])->where('sid', $params['serviceid'])->get();

            $settingArr = explode(',', $data[0]->setting);

            $accessTab = array();

            foreach ($settingArr as $setting) {

                $accessTab[trim($setting)] = 'yes';
            }

            $additionalArr = array();

            foreach (Capsule::table('mod_vmware_ip_list')->where('status', 2)->where('server_id', $serverData[0]->id)->where('forvm', $vm_name)->get() as $additional) {

                $additional = (array) $additional;

                if ($additional['macaddress']) {

                    $macAddress = true;
                }

                $additionalArr[] = $additional;
            }

            $fromExisting = true;

            $vncDetail = explode(' ', $params['customfields']['vnc_detail']);

            $getResources = Capsule::table('mod_vmware_temp_list')->where('server_id', $serverData[0]->id)->where('customname', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->where('datacenter', $customFieldVal['datacenter'])->get();

            $osPort = '';
            if($getResources[0]->port != '')
                $osPort = $getResources[0]->port;
            if (count($getResources) == 0) {
                $clone = false;
                $getResources = Capsule::table('mod_vmware_os_list')->where('server_id', $serverData[0]->id)->where('os_version', $customFieldVal['os_version'])->where('os_family', $customFieldVal['os_type'])->where('datacenter', $customFieldVal['datacenter'])->get();
            }

            $getResources = (array) $getResources[0];
            if (!empty($customFieldVal['hostname_dc'])) {

                $hostDcArr = explode('&&', $customFieldVal['hostname_dc']);

                if ($hostDcArr[0])
                    $hostName = $hostDcArr[0];
                else
                    $hostName = $getResources['hostname'];

                if ($hostDcArr[1])
                    $dc = $hostDcArr[1];
                else
                    $dc = $getResources['datacenter'];
            } else {

                $hostName = $getResources['hostname'];

                $dc = $getResources['datacenter'];
            }
            if ($esxi == 1) {

                $hostName = $getip[1];
            }
            $clone = true;
            $resource_pool_id = ''; //$getResources['resourcepool'];

            $init = $vmData['quickStats']['uptimeSeconds'];

            $customfieldsStr = http_build_query(['customfields' => $params['customfields']]);

            $configoptionsStr = http_build_query(['configoptions' => $params['configoptions']]);

            $clientsdetailsStr = http_build_query(['clientsdetails' => $params['clientsdetails']]);

            $vcpw = base64_encode('Ha' . $decryptPw . 'Rp');

            $reinstallVars = [
                "pid" => $params['pid'], "sid" => $params['serviceid'], "api_server" => $params['configoption3'], "language" => $params['clientsdetails']['language'], "userid" => $params['userid'], "pccode" => $vcpw, "configoption1" => $params['configoption1'], "configoption2" => $params['configoption2'], "configoption3" => $params['configoption3'], "configoption4" => $params['configoption4'], "configoption5" => $params['configoption5'], "configoption6" => $params['configoption6'], "configoption7" => $params['configoption7'], "configoption8" => $params['configoption8'], "configoption9" => $params['configoption9'], "configoption10" => $params['configoption10'], "configoption11" => $params['configoption11'], "configoption12" => $params['configoption12'], "configoption13" => $params['configoption13'], "configoption14" => $params['configoption14'], "configoption15" => $params['configoption15'], "configoption16" => $params['configoption16'], "configoption17" => $params['configoption17'], "configoption18" => $params['configoption18'], "configoption19" => $params['configoption19'], "configoption20" => $params['configoption20'], "configoption21" => $params['configoption21'], "configoption22" => $params['configoption22'], "configoption23" => $params['configoption23'], "configoption24" => $params['configoption24'], "customfields" => $params['customfields'], "configoptions" => $params['configoptions'], "clientsdetails" => $params['clientsdetails'], "vm_name" => $vm_name, "datacenter" => $dataceter_name
            ];

            $reinstallVarsStr = http_build_query(['reinstallVars' => $reinstallVars]);

            $reinstallVarsEncrypt = $WgsVmwareObj->wgsvmwarePwencryption($reinstallVarsStr);
        }

        /* Apps */

        $apps = null;

        if (Capsule::Schema()->hasTable('mod_vmware_apps')) {

            $getApps = Capsule::table('mod_vmware_apps')->groupBy('app_name')->get();

            $apps = [];

            foreach ($getApps as $app) {

                $app = (array) $app;

                $appPath = (dirname(dirname(__DIR__)) . "/addons/vmware/apps/" . $app['app_name'] . "/" . $app['app_name'] . ".php");

                if (file_exists($appPath)) {

                    require_once $appPath;

                    $configarray = call_user_func($app['app_name'] . "_vmware_appConfigArray", '');

                    $app = array_merge($app, ['app' => $configarray['FriendlyName']['Value']]);
                }

                $apps[] = $app;
            }

            $app = $whmcs->get_req_var('app');

            if (!empty($app)) {

                $appPath = (dirname(dirname(__DIR__)) . "/addons/vmware/apps/" . $app . "/" . $app . ".php");

                if (file_exists($appPath)) {

                    require_once $appPath;

                    $appLink = 'clientarea.php?action=productdetails&id=&app=' . $app . '&appmanage=true';

                    if (function_exists($app . "_vmware_appConfigArray"))
                        $configarray = call_user_func($app . "_vmware_appConfigArray", '');

                    $result = Capsule::table('mod_vmware_apps')->where('app_name', $app)->get();

                    $appName = $configarray['FriendlyName']['Value'];

                    $appVars = [
                        'name' => $configarray['FriendlyName']['Value'],
                        'description' => $configarray['Description']['Value'],
                        'version' => $configarray['Version']['Value'],
                        'applink' => $appLink,
                    ];

                    foreach ($result as $data) {

                        $data = (array) $data;

                        $setting = $data['setting'];

                        $value = $data['value'];

                        $appVars[$setting] = decrypt($value);
                    }

                    $_APPLANG = [];

                    if (!empty($_SESSION['Language']))
                        $language = strtolower($_SESSION['Language']);

                    else if (strtolower($params['clientsdetails']['language']) != '')
                        $language = strtolower($params['clientsdetails']['language']);
                    else
                        $language = $CONFIG['Language'];

                    $languagePath = (dirname(dirname(__DIR__)) . "/addons/vmware/apps/" . $app . "/lang/" . $language . ".php");

                    if (file_exists($languagePath)) {

                        include_once $languagePath;
                    }

                    $appVars['lang'] = $_APPLANG;

                    $appVars['moduleparams'] = $params;

                    $appClientAreaOutPut = '';

                    if (function_exists($app . "_vmware_appClientarea"))
                        $appClientAreaOutPut = call_user_func($app . "_vmware_appClientarea", $appVars);
                }
            }
        }

        $consleVars = [
            "pid" => $params['pid'], "sid" => $params['serviceid'], "api_server" => $ApiServerName, "language" => $params['clientsdetails']['language'], "userid" => $params['userid'], "pccode" => $vcpw, "configoption1" => $params['configoption1'], "configoption2" => $params['configoption2'], "configoption3" => $params['configoption3'], "configoption4" => $params['configoption4'], "configoption5" => $params['configoption5'], "configoption6" => $params['configoption6'], "configoption7" => $params['configoption7'], "configoption8" => $params['configoption8'], "configoption9" => $params['configoption9'], "configoption10" => $params['configoption10'], "configoption11" => $params['configoption11'], "configoption12" => $params['configoption12'], "configoption13" => $params['configoption13'], "configoption14" => $params['configoption14'], "configoption15" => $params['configoption15'], "configoption16" => $params['configoption16'], "configoption17" => $params['configoption17'], "configoption18" => $params['configoption18'], "configoption19" => $params['configoption19'], "configoption20" => $params['configoption20'], "configoption21" => $params['configoption21'], "configoption22" => $params['configoption22'], "configoption23" => $params['configoption23'], "configoption24" => $params['configoption24'], "customfields" => $params['customfields'], "configoptions" => $params['configoptions'], "clientsdetails" => $params['clientsdetails'], "vm_name" => $vm_name, "datacenter" => $dataceter_name, 'hostName' => $hostName
        ];

        $consoleVarsStr = http_build_query(['consoleVars' => $consleVars]);



        $consoleVarsEncrypt = $WgsVmwareObj->wgsvmwarePwencryption($consoleVarsStr);
        // echo "<pre>";
        // print_r($vmData['config']['guestId']);
        // die;

        $result = array(
            'templatefile' => 'clientarea',
            'vars' => array(
                'page_url' => $page_url,
                'system_url' => $system_url,
                'vmData' => $vmData,
                'vmImage' => $vmData['config']['guestId'],
                'language' => $_LANG,
                'vmName' => $vm_name,
                'cpu_memory' => $memory,
                'use_memory' => $usedMemory,
                'percentage_mem' => $percentageMem,
                'memory_stat' => $usedMemory . '/' . $memory . ' MB ' . '(' . $percentageMem . '%)',
                'bar_class' => $class,
                'vm_datacenter' => $dc,
                'vm_guest_os' => $osType,
                'vm_guest_os_option' => $os_option,
                'serviceid' => $params['serviceid'],
                'recentTask' => $task,
                'tabAccess' => $accessTab,
                'netWorkDetail' => $netWorkDetail,
                'additionalIp' => $additionalArr,
                'updateTime' => $WgsVmwareObj->get_time($init),
                'vcpw' => base64_encode('Ha' . $decryptPw . 'Rp'),
                'macAddress' => $macAddress,
                'isofiles' => $isoFiles,
                'pid' => $params['pid'],
                "fromExisting" => $fromExisting,
                "vm_port" => $vncDetail[2],
                "vm_token" => md5($vncDetail[1]),
                "vm_password" => base64_encode($vncDetail[1]),
                "vm_host" => $hostName,
                //                "console_link" => str_replace('https', 'http', $CONFIG['SystemURL']) . '/modules/servers/vmware/console.php',
                "console_link" => $consoleUrl,
                "api_server" => $params['configoption3'],
                "pid" => $params['pid'],
                "serviceid" => $params['serviceid'],
                'dcOptions' => $dcOptions,
                'datacenter' => $dataceter_name,
                'resource_pool_id' => $resource_pool_id,
                'esxi' => $esxi,
                'consoleCloneTicket' => $vmrcConsoleUrl,
                'consoleError' => $consoleError,
                //'service_token' => $reinstallVarsEncrypt,
                'console_token' => $consoleVarsEncrypt,
                'apps' => $apps,
                'app_name' => $appName,
                'appoutput' => $appClientAreaOutPut,
                'osport' => $osPort,
                'showallos' => $params['configoption23']
            ),
        );
    } catch (Exception $e) {

        $result = $e->getMessage();
    }

    return $result;
}

function vmware_getLang($params)
{
    global $CONFIG;
    if (!empty($_SESSION['Language']))
        $language = strtolower($_SESSION['Language']);

    else if (strtolower($params['clientsdetails']['language']) != '')
        $language = strtolower($params['clientsdetails']['language']);
    else
        $language = $CONFIG['Language'];

    $langfilename = dirname(__FILE__) . '/lang/' . $language . '.php';

    if (file_exists($langfilename))
        include($langfilename);
    else
        include(dirname(__FILE__) . '/lang/english.php');

    if (count($lang) > 0)
        return $lang;
}

function cleanString($text)
{

    $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÿÀÂÃÄ]/u' => 'A',
        '/[ÿÌÎÿ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u' => ' ', // Literally a single quote
        '/[“‿«»„]/u' => ' ', // Double quote
        '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
    );

    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function convert_vi_to_en($str)
{

    $str = preg_replace("/(ả|à|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);

    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ỿ|ế|ệ|ể|ễ)/", 'e', $str);

    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);

    $str = preg_replace("/(ò|ó|ỿ|ỿ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ỿ|ớ|ợ|ở|ỡ)/", 'o', $str);

    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);

    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);

    $str = preg_replace("/(đ)/", 'd', $str);

    $str = preg_replace("/(À|ÿ|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);

    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);

    $str = preg_replace("/(Ì|ÿ|Ị|Ỉ|Ĩ)/", 'I', $str);

    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|ỿ|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);

    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);

    $str = preg_replace("/(Ỳ|ÿ|Ỵ|Ỷ|Ỹ)/", 'Y', $str);

    $str = preg_replace("/(Ŀ)/", 'D', $str);

    //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));

    return $str;
}

function WGS_VmwareCurlRequest($method, $link, $vsphereUserName, $vSpherePassword, $cookies = null)
{

    $data = base64_encode($vsphereUserName . ':' . $vSpherePassword);

    $ch = curl_init($link);

    curl_setopt($ch, CURLOPT_URL, $link);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);



    curl_setopt($ch, CURLOPT_HEADER, 1);

    //    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method == 'post') {

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('CastleAuthorization' => 'Basic ' . $data)));
    }

    if (!empty($cookies))
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);



    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);

    $info = curl_getinfo($ch);

    $error = curl_error($ch);

    curl_close($ch);

    return ['result' => $result, 'info' => $info, 'error' => $error];
}

function WGS_Vmware_get_headers_from_curl_response($response)
{

    $headers = array();



    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));



    foreach (explode("\r\n", $header_text) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;

        else {

            list($key, $value) = explode(': ', $line);



            $headers[$key] = $value;
        }



    return $headers;
}

function WGS_Vmware_getCookies($data)
{

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $data, $matches);

    return $matches;
}

function WGS_Vmware_get_location($data)
{

    preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $data, $locationmatches);

    return $locationmatches;
}
