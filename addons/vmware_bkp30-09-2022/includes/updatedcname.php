<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$getAllIpServerObj = Capsule::table('mod_vmware_ip_list')->groupby('server_id')->get();

foreach ($getAllIpServerObj as $getAllIpServer) {

    try {
        $getAllIpServer = (array) $getAllIpServer;

        $serverid = $getAllIpServer['server_id'];

        if (!empty($serverid)) {

            $serverData = Capsule::table('mod_vmware_server')->where('id', $serverid)->get();
            $path = str_replace("addons/vmware/includes", "", __DIR__);

            require_once $path . 'servers/vmware/class/class.php';

            $WgsVmwareObj = new WgsVmware();
            $WgsVmwareObj->vmware_includes_files($params);

            $getip = explode('://', $serverData[0]->vsphereip);
            $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);
            $vms = new vmware($getip[1], $serverData[0]->vsphereusername, $decryptPw);

            $datacenter = $vms->list_datacenters();
            $dCenters = $WgsVmwareObj->vmware_object_to_array($datacenter[0]);

            if ($dCenters['RetrievePropertiesResponse']['returnval']) {

                foreach ($dCenters['RetrievePropertiesResponse']['returnval'] as $key => $dCenterValue) {

                    if ($key == 'obj' && $key != '0') {

                        $dcObj = $dCenterValue;
                        $dcName = $dCenters['RetrievePropertiesResponse']['returnval']['propSet'][0]['val'];

                        Capsule::table('mod_vmware_ip_list')->where('server_id', $serverid)->where('datacenter', $dcObj)->update(['datacenter' => $dcName]);
                    } else if ($key == 'propSet' && $key != '0') {
                    } else {

                        $dcObj = $dCenterValue['obj'];
                        $dcName = $dCenterValue['propSet'][0]['val'];

                        Capsule::table('mod_vmware_ip_list')->where('server_id', $serverid)->where('datacenter', $dcObj)->update(['datacenter' => $dcName]);
                    }
                }
            }
        }
        $success = 'Done';
    } catch (Exception $ex) {

        $error = $ex->getMessage();
    }
}
if ($success)
    echo $success;
elseif ($error)
    echo $error;
