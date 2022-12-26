<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (file_exists(__DIR__ . '/class/class.php'))
    require_once __DIR__ . '/class/class.php';

add_hook("AdminAreaPage", 1, function($vars){
    if("true" == $_GET['runquery'] && "vmware" == $_GET['module']){
        
        $WgsVmwareObj = new WgsVmware();

        Capsule::select("ALTER TABLE mod_vmware_hosts_vms MODIFY COLUMN hostid VARCHAR(100)");
        Capsule::select("ALTER TABLE mod_vmware_hosts_vms MODIFY COLUMN vmname VARCHAR(100)");

        $getAllServiceIDs = Capsule::table("mod_vmware_hosts_vms")->get();
    
        $hostArr = [];
        foreach($getAllServiceIDs as $sid){   
            $getPid =  Capsule::table("tblhosting")->select("packageid")->where("id", $sid->sid)->first();
            $getProductData = Capsule::table("tblproducts")->where("id", $getPid->packageid)->first();
            $getVmnameCfId =  Capsule::table("tblcustomfields")->select("id")->where("type", "product")->where("relid", $getPid->packageid)->where("fieldname", "LIKE", "%vm_name%")->first();
            $getVmName = Capsule::table("tblcustomfieldsvalues")->select("value")->where('fieldid', $getVmnameCfId->id)->where('relid', $sid->sid)->first();
            $vmName = $getVmName->value;
            $getHostCfId =  Capsule::table("tblcustomfields")->select("id")->where("type", "product")->where("relid", $getPid->packageid)->where("fieldname", "LIKE", "%hostname_dc%")->first();
            $getHostName = Capsule::table("tblcustomfieldsvalues")->select("value")->where('fieldid', $getHostCfId->id)->where('relid', $sid->sid)->first();
            $host = $getHostName->value;
            $hostNameArr = explode("&&", $host);
            $hostName = $hostNameArr[0];
            if("" != $hostName && "" != $vmName){
                $getServerCfId =  Capsule::table("tblcustomfields")->select("id")->where("type", "product")->where("relid", $getPid->packageid)->where("fieldname", "LIKE", "%vcenter_server_name%")->first();
                $getServerId = Capsule::table("tblcustomfieldsvalues")->select("value")->where('fieldid', $getServerCfId->id)->where('relid', $sid->sid)->first();
                $serverId = $getServerId->value;
                $ApiServerName = ($serverId != '') ? $serverId : $getProductData->configoption3;              
                $hostArr[$hostName][$ApiServerName][$sid->sid] = $vmName;
            }
        }

        foreach($hostArr as $hostName => $hostdata){
            foreach($hostdata as $ApiServerName => $serviceInfo){
                $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->first();
                if (count($serverData) == 0)
                    $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->first();
                if(count($serverData) > 0){    
                    $getip = explode('://', $serverData->vsphereip);
                    $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData->vspherepassword);

                    $vms = new vmware($getip[1], $serverData->vsphereusername, $decryptPw);
                    $host = $vms->getHostnames($hostName);
                    if("" != $host->reference->_){
                        foreach($serviceInfo as $sid => $vmname){
                            if (Capsule::table("mod_vmware_hosts_vms")->where('sid', $sid)->count() > 0) {
                                Capsule::table("mod_vmware_hosts_vms")->where('sid', $sid)->update(['hostid' => $host->reference->_, 'vmname' => $vmname]);
                            } else {
                                Capsule::table("mod_vmware_hosts_vms")->insert(['sid' => $sid, 'hostid' => $host->reference->_, 'vmname' => $vmname]);
                            }
                        }
                    }
                }
            }
        }
    }    
});